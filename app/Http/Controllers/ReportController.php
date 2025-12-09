<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\FinancialInsight;
use App\Services\GeminiAgentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Filter & Tipe Laporan
        $type = $request->input('type', 'monthly'); // Default: Bulanan
        $date = $request->input('date', date('Y-m-d')); // Tanggal referensi
        $refDate = Carbon::parse($date);

        // 2. Tentukan Period Key (ID Unik Laporan)
        if ($type === 'weekly') {
            // Format: "2025-W49" (Tahun - Minggu ke-sekian)
            $periodKey = $refDate->format('o-W'); 
        } else {
            // Format: "2025-12" (Tahun - Bulan)
            $periodKey = $refDate->format('Y-m');
        }

        // 3. Cari Laporan
        $report = FinancialInsight::where('user_id', $user->id)
            ->where('type', $type)
            ->where('period_key', $periodKey)
            ->first();

        return view('reports.index', compact('report', 'type', 'date', 'refDate'));
    }

    public function generate(Request $request, GeminiAgentService $ai)
    {
        $user = Auth::user();
        $type = $request->input('type'); // 'weekly' atau 'monthly'
        $date = $request->input('date');
        $refDate = Carbon::parse($date);

        // --- 1. SETUP QUERY ---
        $currentQuery = Expense::where('user_id', $user->id);
        $prevQuery = Expense::where('user_id', $user->id);

        if ($type === 'weekly') {
            // MINGGUAN: Senin s/d Minggu
            $start = $refDate->copy()->startOfWeek();
            $end = $refDate->copy()->endOfWeek();
            $periodKey = $refDate->format('o-W');
            $periodName = "Minggu ke-" . $refDate->weekOfYear . " " . $refDate->year . " (" . $start->format('d M') . " - " . $end->format('d M') . ")";

            // Filter Minggu Ini
            $currentQuery->whereBetween('date', [$start, $end]);
            
            // Filter Minggu Lalu (Untuk Tren)
            $prevStart = $start->copy()->subWeek();
            $prevEnd = $end->copy()->subWeek();
            $prevQuery->whereBetween('date', [$prevStart, $prevEnd]);

        } else {
            // BULANAN: Tanggal 1 s/d Akhir Bulan
            $periodKey = $refDate->format('Y-m');
            $periodName = "Bulan " . $refDate->format('F Y');

            // Filter Bulan Ini
            $currentQuery->whereMonth('date', $refDate->month)->whereYear('date', $refDate->year);
            
            // Filter Bulan Lalu
            $prevDate = $refDate->copy()->subMonth();
            $prevQuery->whereMonth('date', $prevDate->month)->whereYear('date', $prevDate->year);
        }

        // --- 2. HITUNG DATA ---
        $dailyExpenses = $currentQuery->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')->get();
        $totalCurrent = $dailyExpenses->sum('total');
        $totalLast = $prevQuery->sum('amount');

        // Hitung Tren
        $trendPercent = 0;
        if ($totalLast > 0) $trendPercent = (($totalCurrent - $totalLast) / $totalLast) * 100;
        elseif ($totalCurrent > 0) $trendPercent = 100;
        
        $trendText = ($trendPercent > 0 ? "NAIK " : "TURUN ") . number_format(abs($trendPercent), 1) . "%";

        // Deteksi Spike (Boros)
        $avgDaily = $dailyExpenses->count() > 0 ? $dailyExpenses->avg('total') : 0;
        $wastefulDates = [];
        foreach ($dailyExpenses as $day) {
            if ($day->total > ($avgDaily * 1.5)) { // Ambang batas 1.5x rata-rata
                $wastefulDates[] = $day->date;
            }
        }

        // --- 3. PANGGIL AI ---
        $contextData = [
            'period_name' => $periodName,
            'total_current' => $totalCurrent,
            'trend_text' => $trendText,
            'avg_daily' => $avgDaily,
            'wasteful_dates' => $wastefulDates
        ];

        $aiResult = $ai->analyzeForReport($contextData);

        if (!$aiResult) return back()->with('error', 'AI Sedang Sibuk');

        // --- 4. SIMPAN HASIL ---
        FinancialInsight::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type, 'period_key' => $periodKey],
            [
                'status' => count($wastefulDates) > 2 ? 'danger' : 'safe',
                'ai_analysis' => $aiResult['analysis'],
                'ai_recommendation' => $aiResult['recommendation'],
                'wasteful_dates' => $wastefulDates,
                'total_expense' => $totalCurrent,
                'percentage_change' => $trendPercent
            ]
        );

        return redirect()->route('reports.index', ['type' => $type, 'date' => $date])
            ->with('success', 'Analisis berhasil dibuat!');
    }
}