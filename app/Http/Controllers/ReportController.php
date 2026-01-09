<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Activity;
use App\Models\FinancialInsight;
use App\Services\FinancialAiService;

class ReportController extends Controller
{
    /**
     * Menampilkan dashboard laporan (Default View)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type', 'monthly');
        $date = $request->input('date', date('Y-m-d'));

        $period = $this->getPeriodMetadata($type, $date);

        $report = FinancialInsight::where('user_id', $user->id)
            ->where('type', $type)
            ->where('period_key', $period['key'])
            ->first();

        // Range Kalender View (H-1 Bulan s/d H+1 Bulan)
        $viewStart = Carbon::parse($date)->startOfMonth()->subMonth();
        $viewEnd = Carbon::parse($date)->endOfMonth()->addMonth();

        $dates = $this->getAllTransactionDates($user->id, $viewStart, $viewEnd);

        $history = FinancialInsight::where('user_id', $user->id)
            ->where('id', '!=', $report->id ?? 0)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('reports.index', compact('report', 'type', 'date', 'history', 'dates'));
    }

    /**
     * Menampilkan detail laporan spesifik dari history
     */
    public function show($id)
    {
        $user = Auth::user();
        $report = FinancialInsight::where('user_id', $user->id)->findOrFail($id);

        $date = $this->resolveDateFromKey($report->type, $report->period_key, $report->created_at);

        $viewStart = Carbon::parse($date)->startOfMonth()->subMonth();
        $viewEnd = Carbon::parse($date)->endOfMonth()->addMonth();

        $dates = $this->getAllTransactionDates($user->id, $viewStart, $viewEnd);

        return view('reports.index', [
            'report' => $report,
            'type' => $report->type,
            'date' => $date,
            'history' => FinancialInsight::where('user_id', $user->id)->where('id', '!=', $id)->latest()->limit(5)->get(),
            'dates' => $dates,
            'is_detail_view' => true
        ]);
    }

    public function history()
    {
        $reports = FinancialInsight::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('reports.history', compact('reports'));
    }

    /**
     * MAIN LOGIC: Orchestrator untuk Analisis AI
     */
    public function generate(Request $request, FinancialAiService $ai)
    {
        $user = Auth::user();
        $type = $request->input('type');
        $date = $request->input('date');

        // 1. Tentukan Periode Waktu
        $period = $this->getPeriodMetadata($type, $date);

        // 2. Fetch Data Mentah dari Database
        $expenses = Expense::with('activity') // Eager load activity
            ->where('user_id', $user->id)
            ->whereBetween('date', [$period['start'], $period['end']])
            ->get();

        $incomes = Income::where('user_id', $user->id)
            ->whereBetween('date_received', [$period['start'], $period['end']])
            ->get();

        if ($expenses->isEmpty() && $incomes->isEmpty()) {
            return back()->with('error', 'Data transaksi kosong. Silakan isi data dulu.');
        }

        // 3. JALANKAN "PHP TOOLS" (Pre-computation)
        // Matematika dilakukan di sini, bukan di AI.

        // Tool A: Hitung Neraca (Income, Expense, Balance, Savings %)
        $balanceMetrics = $this->toolCalculateBalance($incomes, $expenses);

        // Tool B: Analisis Kebiasaan (Kategori & Activity Termahal)
        $spendingMetrics = $this->toolAnalyzeSpending($expenses);

        // Tool C: Deteksi Anomali (Hari Boros)
        $anomalyMetrics = $this->toolDetectAnomalies($expenses);

        // Tool D: Hitung Tren (Compare vs Periode Lalu)
        $trendMetric = $this->toolCalculateTrend($user->id, $balanceMetrics['total_expense'], $period);

        // 4. PACKING DATA (Kirim Paket Matang ke AI)
        $contextData = array_merge(
            $balanceMetrics,
            $spendingMetrics,
            $anomalyMetrics,
            [
                'trend' => $trendMetric,
                'job' => $user->job ?? 'General',
                'location' => $user->address ?? 'ID'
            ]
        );

        // 5. Panggil AI (DeepSeek Only)
        $aiResult = $ai->analyzeReport($contextData);

        if (!$aiResult) {
            return back()->with('error', 'Layanan AI sedang sibuk. Silakan coba lagi nanti.');
        }

        // 6. Simpan Hasil ke Database
        FinancialInsight::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type, 'period_key' => $period['key']],
            [
                'status' => $this->validateStatus($aiResult['status'] ?? 'warning'),
                'ai_analysis' => $aiResult['analysis'],
                'ai_recommendation' => $aiResult['recommendation'],

                // Simpan snapshot angka agar history konsisten
                'total_expense' => $balanceMetrics['total_expense'],
                'total_income' => $balanceMetrics['total_income'],
                'balance' => $balanceMetrics['balance'],
                'percentage_change' => $trendMetric,
                'wasteful_dates' => $anomalyMetrics['waste_dates'] // Array tanggal untuk kalender
            ]
        );

        return redirect()->route('reports.index', ['type' => $type, 'date' => $date])
            ->with('success', 'Analisis AI berhasil diselesaikan!');
    }

    // =========================================================================
    // MODULAR TOOLS (Private Calculation Functions)
    // =========================================================================

    /**
     * Tool A: Menghitung Total Income, Expense, Balance, dan Savings Rate
     */
    private function toolCalculateBalance($incomes, $expenses)
    {
        $totalInc = $incomes->sum('amount');
        $totalExp = $expenses->sum('amount');
        $balance = $totalInc - $totalExp;

        // --- LOGIKA BARU: Cek Income Regular vs Tidak ---
        $regularInc = $incomes->where('is_regular', true)->sum('amount');
        $irregularInc = $incomes->where('is_regular', false)->sum('amount');

        // Tentukan Label Profil Pendapatan untuk AI
        $incomeProfile = 'Tidak Ada Pemasukan';
        if ($totalInc > 0) {
            if ($irregularInc == 0) {
                $incomeProfile = 'Stabil (Gaji Tetap)';
            } elseif ($regularInc == 0) {
                $incomeProfile = 'Fluktuatif (Freelance/Bisnis)';
            } else {
                $pctRegular = round(($regularInc / $totalInc) * 100);
                $incomeProfile = "Campuran ({$pctRegular}% Tetap)";
            }
        }

        // Hitung Savings Rate
        $saveRate = 0;
        if ($totalInc > 0) {
            $saveRate = round(($balance / $totalInc) * 100);
        }

        return [
            'total_income' => (int) $totalInc,
            'total_expense' => (int) $totalExp,
            'balance' => (int) $balance,
            'save_rate' => $saveRate,
            'income_profile' => $incomeProfile, // <--- Data baru dikirim ke AI
        ];
    }

    /**
     * Tool B: Mencari Kategori Terboros & Aktivitas Spesifik Termahal
     */
    private function toolAnalyzeSpending($expenses)
    {
        if ($expenses->isEmpty()) {
            return ['top_cat' => '-', 'top_cat_pct' => 0, 'top_activity' => null];
        }

        $total = $expenses->sum('amount');

        // 1. Cari Kategori dengan total pengeluaran terbesar
        $catStats = $expenses->groupBy('category')
            ->map(fn($row) => $row->sum('amount'))
            ->sortDesc();

        $topCat = $catStats->keys()->first();
        $topCatAmount = $catStats->first();
        $topCatPct = $total > 0 ? round(($topCatAmount / $total) * 100) : 0;

        // 2. Cari Aktivitas Termahal (Single Event)
        // Menggunakan relasi 'activity' yang sudah di-load
        $expensiveExpense = $expenses->whereNotNull('activity_id')
            ->sortByDesc('amount')
            ->first();

        $topActivity = null;
        if ($expensiveExpense && $expensiveExpense->activity) {
            // Contoh: "Liburan Bali (Transport)"
            $topActivity = $expensiveExpense->activity->title .
                ($expensiveExpense->category ? " ({$expensiveExpense->category})" : "");
        }

        return [
            'top_cat' => $topCat,
            'top_cat_pct' => $topCatPct,
            'top_activity' => $topActivity
        ];
    }

    /**
     * Tool C: Deteksi Tanggal Boros (Anomaly Detection)
     */
    private function toolDetectAnomalies($expenses)
    {
        if ($expenses->isEmpty())
            return ['waste_count' => 0, 'waste_dates' => []];

        // Kelompokkan per hari
        $daily = $expenses->groupBy('date')->map(fn($row) => $row->sum('amount'));

        // Hitung rata-rata harian
        $avgDaily = $daily->avg();

        // Threshold: Dianggap boros jika pengeluaran > 1.5x rata-rata
        $threshold = $avgDaily * 1.5;

        $wasteDates = [];
        foreach ($daily as $date => $amount) {
            if ($amount > $threshold) {
                $wasteDates[] = $date;
            }
        }

        return [
            'waste_count' => count($wasteDates), // Dikirim ke AI
            'waste_dates' => $wasteDates         // Disimpan ke DB
        ];
    }

    /**
     * Tool D: Kalkulasi Trend vs Periode Sebelumnya
     */
    private function toolCalculateTrend($userId, $currentTotal, $period)
    {
        // Query terpisah untuk efisiensi memori
        $prevTotal = Expense::where('user_id', $userId)
            ->whereBetween('date', [$period['prev_start'], $period['prev_end']])
            ->sum('amount');

        if ($prevTotal == 0) {
            return $currentTotal > 0 ? 100 : 0;
        }

        // + berarti naik (boros), - berarti turun (hemat)
        return round((($currentTotal - $prevTotal) / $prevTotal) * 100);
    }

    // =========================================================================
    // HELPER METHODS (Dates & Metadata)
    // =========================================================================

    private function getPeriodMetadata($type, $dateInput)
    {
        $refDate = Carbon::parse($dateInput);

        if ($type === 'weekly') {
            return [
                'start' => $refDate->copy()->subDays(6)->startOfDay(),
                'end' => $refDate->copy()->endOfDay(),
                'key' => $refDate->format('o-W'),
                'name' => "7 Hari Terakhir",
                'prev_start' => $refDate->copy()->subDays(13)->startOfDay(),
                'prev_end' => $refDate->copy()->subDays(7)->endOfDay(),
            ];
        }

        // Monthly / 30 Days Rolling
        return [
            'start' => $refDate->copy()->subMonth()->startOfDay(),
            'end' => $refDate->copy()->endOfDay(),
            'key' => $refDate->format('Y-m-d') . '-rolling',
            'name' => "30 Hari Terakhir",
            'prev_start' => $refDate->copy()->subMonths(2)->startOfDay(),
            'prev_end' => $refDate->copy()->subMonth()->endOfDay(),
        ];
    }

    private function getAllTransactionDates($userId, $start, $end)
    {
        return [
            'expenses' => Expense::where('user_id', $userId)
                ->whereBetween('date', [$start, $end])
                ->pluck('date')->map(fn($d) => substr($d, 0, 10))->unique()->values()->toArray(),
            'incomes' => Income::where('user_id', $userId)
                ->whereBetween('date_received', [$start, $end])
                ->pluck('date_received')->map(fn($d) => substr($d, 0, 10))->unique()->values()->toArray(),
            'activities' => Activity::where('user_id', $userId)
                ->whereBetween('date_start', [$start, $end])
                ->pluck('date_start')->map(fn($d) => substr($d, 0, 10))->unique()->values()->toArray(),
        ];
    }

    private function resolveDateFromKey($type, $key, $createdAt)
    {
        if ($type === 'weekly') {
            $parts = explode('-', $key);
            // Fallback safety
            if (count($parts) < 2)
                return $createdAt->format('Y-m-d');
            return Carbon::now()->setISODate($parts[0], substr($parts[1], 1))->startOfWeek()->format('Y-m-d');
        }

        if ($type === 'monthly') {
            // Cek jika format rolling "2023-01-01-rolling" atau monthly "2023-01"
            if (str_contains($key, 'rolling')) {
                return str_replace('-rolling', '', $key);
            }
            return $key . '-01';
        }

        return $createdAt->format('Y-m-d');
    }

    private function validateStatus($status)
    {
        return in_array(strtolower($status), ['safe', 'warning', 'danger']) ? strtolower($status) : 'warning';
    }
}