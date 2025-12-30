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

        // --- PERBAIKAN: Ambil Semua Tanggal (Expense, Income, Activity) ---
        // Ambil range view kalender (H-1 bulan s/d H+1 bulan agar navigasi lancar)
        $viewStart = Carbon::parse($date)->startOfMonth()->subMonth(); 
        $viewEnd   = Carbon::parse($date)->endOfMonth()->addMonth();

        // Panggil helper baru
        $dates = $this->getAllTransactionDates($user->id, $viewStart, $viewEnd);

        $history = FinancialInsight::where('user_id', $user->id)
            ->where('id', '!=', $report->id ?? 0)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Kirim variabel 'dates' (bukan expenseDates)
        return view('reports.index', compact('report', 'type', 'date', 'history', 'dates'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $report = FinancialInsight::where('user_id', $user->id)->findOrFail($id);

        $date = $report->created_at->format('Y-m-d'); 
        if ($report->type === 'weekly') {
            $parts = explode('-', $report->period_key);
            $date = Carbon::now()->setISODate($parts[0], substr($parts[1], 1))->startOfWeek()->format('Y-m-d');
        } elseif ($report->type === 'monthly') {
            $date = $report->period_key . '-01';
        }

        $viewStart = Carbon::parse($date)->startOfMonth()->subMonth(); 
        $viewEnd   = Carbon::parse($date)->endOfMonth()->addMonth();
        
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

    public function generate(Request $request, FinancialAiService $ai)
    {
        $user = Auth::user();
        $type = $request->input('type');
        $date = $request->input('date');

        $period = $this->getPeriodMetadata($type, $date);
        $metrics = $this->getFinancialMetrics($user, $period);

        $contextData = [
            'period_name' => $period['name'],
            'total_income' => $metrics['total_income'],
            'total_expense' => $metrics['total_expense'],
            'balance' => $metrics['balance'],
            'trend_text' => $metrics['trend_text'],
            'wasteful_dates' => $metrics['wasteful_dates'],
            'user_job' => $user->job ?? 'Tidak disebutkan',
            'user_city' => $user->address ?? 'Indonesia',
            'income_sources' => $metrics['income_sources'],
            'income_structure' => $metrics['income_structure'],
        ];

        $aiResult = $ai->analyzeReport($contextData, $request->input('ai_model'));

        if (!$aiResult) {
            return back()->with('error', 'Layanan AI sedang sibuk.');
        }

        FinancialInsight::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type, 'period_key' => $period['key']],
            [
                'status' => $this->validateStatus($aiResult['status'] ?? 'warning'),
                'ai_analysis' => $aiResult['analysis'],
                'ai_recommendation' => $aiResult['recommendation'],
                'wasteful_dates' => $metrics['wasteful_dates'],
                'total_expense' => $metrics['total_expense'],
                'total_income' => $metrics['total_income'],
                'balance' => $metrics['balance'],
                'percentage_change' => $metrics['trend_percent']
            ]
        );

        return redirect()->route('reports.index', ['type' => $type, 'date' => $date])
            ->with('success', 'Analisis AI berhasil diselesaikan!');
    }

    // --- HELPER METHODS ---

    /**
     * Mengambil Tanggal Unik untuk Expense, Income, dan Activity
     */
    private function getAllTransactionDates($userId, $start, $end)
    {
        // Format tanggal dipaksa ke Y-m-d untuk mencocokkan dengan JS
        return [
            'expenses' => Expense::where('user_id', $userId)
                ->whereBetween('date', [$start, $end])
                ->pluck('date')
                ->map(fn($d) => substr($d, 0, 10)) // Ambil Y-m-d saja
                ->unique()->values()->toArray(),

            'incomes' => Income::where('user_id', $userId)
                ->whereBetween('date_received', [$start, $end])
                ->pluck('date_received')
                ->map(fn($d) => substr($d, 0, 10))
                ->unique()->values()->toArray(),

            'activities' => Activity::where('user_id', $userId)
                ->whereBetween('date_start', [$start, $end])
                ->pluck('date_start')
                ->map(fn($d) => substr($d, 0, 10))
                ->unique()->values()->toArray(),
        ];
    }

    private function getPeriodMetadata($type, $dateInput)
    {
        $refDate = Carbon::parse($dateInput);

        if ($type === 'weekly') {
            return [
                'start' => $refDate->copy()->subDays(6)->startOfDay(),
                'end' => $refDate->copy()->endOfDay(),
                'key' => $refDate->format('o-W'),
                'name' => "7 Hari Terakhir (" . $refDate->copy()->subDays(6)->format('d M') . " - " . $refDate->format('d M Y') . ")",
                'prev_start' => $refDate->copy()->subDays(13)->startOfDay(),
                'prev_end' => $refDate->copy()->subDays(7)->endOfDay(),
            ];
        }

        return [
            'start' => $refDate->copy()->startOfMonth(),
            'end' => $refDate->copy()->endOfMonth(),
            'key' => $refDate->format('Y-m'),
            'name' => "Bulan " . $refDate->translatedFormat('F Y'),
            'prev_start' => $refDate->copy()->subMonth()->startOfMonth(),
            'prev_end' => $refDate->copy()->subMonth()->endOfMonth(),
        ];
    }

    private function getFinancialMetrics($user, $period)
    {
        $expenses = Expense::where('user_id', $user->id)->whereBetween('date', [$period['start'], $period['end']])->get();
        $incomes = Income::where('user_id', $user->id)->whereBetween('date_received', [$period['start'], $period['end']])->get();
        $prevExpenseTotal = Expense::where('user_id', $user->id)->whereBetween('date', [$period['prev_start'], $period['prev_end']])->sum('amount');

        $totalExpense = $expenses->sum('amount');
        $totalIncome = $incomes->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $trendPercent = 0;
        if ($prevExpenseTotal > 0) {
            $trendPercent = (($totalExpense - $prevExpenseTotal) / $prevExpenseTotal) * 100;
        } elseif ($totalExpense > 0) {
            $trendPercent = 100;
        }
        $trendText = ($trendPercent > 0 ? "NAIK " : "TURUN ") . number_format(abs($trendPercent), 1) . "%";

        // Income Structure Logic
        $fixedIncome = $incomes->where('is_regular', true)->sum('amount');
        $variableIncome = $incomes->where('is_regular', false)->sum('amount');
        
        $incomeStructure = "Tidak ada pemasukan.";
        if ($totalIncome > 0) {
            if ($fixedIncome > 0 && $variableIncome == 0) $incomeStructure = "100% Stabil (Gaji Tetap).";
            elseif ($fixedIncome == 0 && $variableIncome > 0) $incomeStructure = "100% Fluktuatif (Freelance).";
            else {
                $pct = round(($fixedIncome/$totalIncome)*100);
                $incomeStructure = "HYBRID: {$pct}% Tetap + " . (100-$pct) . "% Variabel.";
            }
        }

        // Wasteful Logic
        $daily = $expenses->groupBy('date')->map(fn($r) => $r->sum('amount'));
        $avg = $daily->count() > 0 ? $daily->avg() : 0;
        $wastefulDates = [];
        foreach ($daily as $dt => $amt) {
            if ($amt > ($avg * 1.5)) $wastefulDates[] = $dt;
        }

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'balance' => $balance,
            'trend_percent' => $trendPercent,
            'trend_text' => $trendText,
            'wasteful_dates' => $wastefulDates,
            'income_sources' => $incomes->pluck('source')->unique()->implode(', ') ?: 'Tidak terdeteksi',
            'income_structure' => $incomeStructure,
        ];
    }

    private function validateStatus($status)
    {
        return in_array(strtolower($status), ['safe', 'warning', 'danger']) ? strtolower($status) : 'warning';
    }
}