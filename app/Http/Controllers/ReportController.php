<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use App\Models\FinancialInsight;
use App\Services\GeminiAgentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $type = $request->input('type', 'monthly');
        $date = $request->input('date', date('Y-m-d'));
        $refDate = Carbon::parse($date);

        if ($type === 'weekly') {
            $periodKey = $refDate->format('o-W');
        } else {
            $periodKey = $refDate->format('Y-m');
        }

        // 1. Ambil Laporan Aktif (Sesuai Filter)
        $report = FinancialInsight::where('user_id', $user->id)
            ->where('type', $type)
            ->where('period_key', $periodKey)
            ->first();
        // 2. LOGIKA BARU: Query Tanggal Transaksi Langsung dari Tabel Expense
        $expenseDates = []; // Default kosong

        if ($type === 'weekly') {
            $start = $refDate->copy()->startOfWeek();
            $end = $refDate->copy()->endOfWeek();

            $expenseDates = Expense::where('user_id', $user->id)
                ->whereBetween('date', [$start, $end])
                ->pluck('date') // Ambil kolom tanggal saja
                ->unique()      // Hapus duplikat
                ->values()      // Reset index array
                ->toArray();
        } else {
            // Bulanan
            $expenseDates = Expense::where('user_id', $user->id)
                ->whereMonth('date', $refDate->month)
                ->whereYear('date', $refDate->year)
                ->pluck('date')
                ->unique()
                ->values()
                ->toArray();
        }
        // 2. TAMBAHAN: Ambil Riwayat Laporan (History)
        // Mengambil 5 laporan terakhir selain laporan yang sedang ditampilkan
        $history = FinancialInsight::where('user_id', $user->id)
            ->where('id', '!=', $report->id ?? 0) // Jangan tampilkan yang sedang dibuka di atas
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('reports.index', compact('report', 'type', 'date', 'history', 'expenseDates'));
    }
    public function show($id)
    {
        $user = Auth::user();

        $report = FinancialInsight::where('user_id', $user->id)->findOrFail($id);

        $type = $report->type;
        $date = $report->created_at->format('Y-m-d');
        // Default tanggal ke tanggal pembuatan
        $parts = explode('-', $report->period_key);
        $year = $parts[0];
        $suffix = $parts[1];
        $expenseDates = [];
        if ($type === 'weekly') {
            // Logika untuk mengubah Minggu ke Tanggal Start/End agak kompleks, 
            // tapi kita bisa gunakan Carbon setISODate
            $dt = Carbon::now()->setISODate($year, $suffix);
            $start = $dt->copy()->startOfWeek();
            $end = $dt->copy()->endOfWeek();

            $expenseDates = Expense::where('user_id', $user->id)
                ->whereBetween('date', [$start, $end])
                ->pluck('date')->unique()->values()->toArray();

            $date = $start->format('Y-m-d'); // Set tanggal date picker ke awal minggu history
        } else {
            // Bulanan
            $expenseDates = Expense::where('user_id', $user->id)
                ->whereMonth('date', $suffix)
                ->whereYear('date', $year)
                ->pluck('date')->unique()->values()->toArray();

            $date = "$year-$suffix-01"; // Set tanggal date picker ke awal bulan history
        }
        return view('reports.index', [
            'report' => $report,
            'type' => $type,
            'date' => $date,
            'history' => FinancialInsight::where('user_id', $user->id)->where('id', '!=', $id)->latest()->limit(5)->get(),
            'expenseDates' => $expenseDates,
            'is_detail_view' => true // Flag penanda ini mode detail history
        ]);
    }
    public function generate(Request $request, GeminiAgentService $ai)
    {
        $user = Auth::user();
        $type = $request->input('type');
        $date = $request->input('date');
        $refDate = Carbon::parse($date);

        // 1. SETUP QUERY AWAL
        $expenseQuery = Expense::where('user_id', $user->id);
        $incomeQuery = Income::where('user_id', $user->id);
        $prevExpenseQuery = Expense::where('user_id', $user->id);

        // Hapus perhitungan premature di sini (yang sebelumnya ada di baris 123-127)

        // Data Profil User
        $job = $user->job ?? 'Tidak disebutkan';
        $city = $user->address ?? 'Indonesia';

        // 2. TERAPKAN FILTER TANGGAL (MINGGUAN/BULANAN)
        if ($type === 'weekly') {
            // MINGGUAN
            $start = $refDate->copy()->startOfWeek();
            $end = $refDate->copy()->endOfWeek();
            $periodKey = $refDate->format('o-W');
            $periodName = "Minggu ke-" . $refDate->weekOfYear . " " . $refDate->year;

            // Filter Query Utama
            $expenseQuery->whereBetween('date', [$start, $end]);
            $incomeQuery->whereBetween('date_received', [$start, $end]); // Asumsi kolom date di Income bernama 'date'

            // Filter Tren (Minggu Lalu)
            $prevStart = $start->copy()->subWeek();
            $prevEnd = $end->copy()->subWeek();
            $prevExpenseQuery->whereBetween('date', [$prevStart, $prevEnd]);

        } else {
            // BULANAN
            $periodKey = $refDate->format('Y-m');
            $periodName = "Bulan " . $refDate->format('F Y');

            // Filter Query Utama
            $expenseQuery->whereMonth('date', $refDate->month)->whereYear('date', $refDate->year);
            $incomeQuery->whereMonth('date_received', $refDate->month)->whereYear('date_received', $refDate->year);

            // Filter Tren (Bulan Lalu)
            $prevDate = $refDate->copy()->subMonth();
            $prevExpenseQuery->whereMonth('date', $prevDate->month)->whereYear('date', $prevDate->year);
        }

        // 3. BARU HITUNG TOTALNYA DI SINI (SETELAH FILTER)
        $dailyExpenses = $expenseQuery->selectRaw('date, SUM(amount) as total')->groupBy('date')->get();

        $totalExpense = $dailyExpenses->sum('total'); // Ini sekarang SUDAH BENAR (Periode ini saja)
        $totalIncome = $incomeQuery->sum('amount');   // Ini sekarang SUDAH BENAR (Periode ini saja)
        $totalLastExpense = $prevExpenseQuery->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // Ambil Sumber Pemasukan
        $incomeSources = $incomeQuery->pluck('source') // Sesuaikan nama kolom (source/category)
            ->unique()
            ->implode(', ');

        if (empty($incomeSources))
            $incomeSources = "Tidak terdeteksi";

        // Hitung Tren
        $trendPercent = 0;
        if ($totalLastExpense > 0) {
            $trendPercent = (($totalExpense - $totalLastExpense) / $totalLastExpense) * 100;
        } elseif ($totalExpense > 0) {
            $trendPercent = 100;
        }
        $trendText = ($trendPercent > 0 ? "NAIK " : "TURUN ") . number_format(abs($trendPercent), 1) . "%";

        // Deteksi Boros (Spike)
        $avgDaily = $dailyExpenses->count() > 0 ? $dailyExpenses->avg('total') : 0;
        $wastefulDates = [];
        foreach ($dailyExpenses as $day) {
            if ($day->total > ($avgDaily * 1.5)) {
                $wastefulDates[] = $day->date;
            }
        }

        // Tentukan Status (Logic Baru)


        // 4. KIRIM KE AI
        $contextData = [
            'period_name' => $periodName,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $balance,
            'trend_text' => $trendText,
            'wasteful_dates' => $wastefulDates,
            'user_job' => $job,
            'user_city' => $city,
            'income_sources' => $incomeSources
        ];

        $aiResult = $ai->analyzeForReport($contextData);

        if (!$aiResult)
            return back()->with('error', 'AI Sedang Sibuk');
        // Kita ambil status dari AI, jika AI error/lupa kasih status, default ke 'warning'
        $aiStatus = $aiResult['status'] ?? 'warning';

        // Validasi agar hanya menerima: safe, warning, danger (untuk mencegah error tampilan)
        if (!in_array($aiStatus, ['safe', 'warning', 'danger'])) {
            $aiStatus = 'warning';
        }
        // 5. SIMPAN HASIL
        // 5. SIMPAN HASIL
        FinancialInsight::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type, 'period_key' => $periodKey],
            [
                'status' => $aiStatus,
                'ai_analysis' => $aiResult['analysis'],
                'ai_recommendation' => $aiResult['recommendation'],
                'wasteful_dates' => $wastefulDates,
                
                // DATA KEUANGAN LENGKAP
                'total_expense' => $totalExpense,
                'total_income'  => $totalIncome, // <--- Simpan Pemasukan
                'balance'       => $balance,     // <--- Simpan Sisa Uang
                
                'percentage_change' => $trendPercent
            ]
        );

        return redirect()->route('reports.index', ['type' => $type, 'date' => $date])
            ->with('success', 'Analisis berhasil dibuat!');
    }
}