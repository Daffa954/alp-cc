<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // --- 1. STATISTIK BULAN INI ---
        $totalExpense = Expense::where('user_id', $user->id)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $totalIncome = Income::where('user_id', $user->id)
            ->whereMonth('date_received', $now->month)
            ->whereYear('date_received', $now->year)
            ->sum('amount');

        // --- 2. STATISTIK MINGGUAN (Rata-rata Harian) ---
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        $weeklyExpense = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        $weeklyAverage = $weeklyExpense / 7;

        // --- 3. DATA UNTUK KALENDER (Harian) ---
        $calendarData = [];

        // Ambil Pengeluaran Bulanan & Hitung Total per Tanggal
        $monthlyExpenses = Expense::where('user_id', $user->id)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->get();

        foreach ($monthlyExpenses as $exp) {
            $date = Carbon::parse($exp->date)->format('Y-m-d');
            
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [
                    'total_expense' => 0, 
                    'total_income' => 0, 
                    'expenses' => [], 
                    'activities' => []
                ];
            }

            $calendarData[$date]['expenses'][] = [
                'amount' => number_format($exp->amount, 0, ',', '.'),
                'category' => $exp->category,
                'desc' => $exp->description
            ];
            $calendarData[$date]['total_expense'] += $exp->amount;
        }

        // Ambil Pemasukan Bulanan & Hitung Total per Tanggal
        $monthlyIncomes = Income::where('user_id', $user->id)
            ->whereMonth('date_received', $now->month)
            ->whereYear('date_received', $now->year)
            ->get();

        foreach ($monthlyIncomes as $inc) {
            $date = Carbon::parse($inc->date_received)->format('Y-m-d');

            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [
                    'total_expense' => 0, 
                    'total_income' => 0, 
                    'expenses' => [], 
                    'activities' => []
                ];
            }

            $calendarData[$date]['total_income'] += $inc->amount;
        }

        // Ambil Aktivitas Bulanan
        $monthlyActivities = Activity::where('user_id', $user->id)
            ->whereMonth('date_start', $now->month)
            ->whereYear('date_start', $now->year)
            ->get();

        foreach ($monthlyActivities as $act) {
            $date = Carbon::parse($act->date_start)->format('Y-m-d');
            
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [
                    'total_expense' => 0, 
                    'total_income' => 0, 
                    'expenses' => [], 
                    'activities' => []
                ];
            }
            $calendarData[$date]['activities'][] = [
                'title' => $act->title,
            ];
        }

        // --- 4. DATA GRAFIK (Expense Trend 30 Hari Terakhir) ---
        $chartData = Expense::where('user_id', $user->id)
            ->where('date', '>=', $now->copy()->subDays(30))
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $currentDate = $now->copy()->subDays($i);
            $dateKey = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d M');

            $dayData = $chartData->firstWhere('date', $dateKey);
            $data[] = $dayData ? $dayData->total : 0;
        }

        $expenseTrend = [
            'labels' => $labels,
            'data' => $data
        ];

        // --- 5. BREAKDOWN KATEGORI ---
        $categoryBreakdown = Expense::where('user_id', $user->id)
            ->whereMonth('date', $now->month)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category,
                    'total' => $item->total,
                    'count' => $item->count,
                    'icon' => 'fas fa-tag',
                    'color' => '#ff6b00'
                ];
            });

        // --- 6. AKTIVITAS TERBARU ---
        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- 7. REKOMENDASI PINTAR ---
        $recommendations = [];
        $budgetLimit = $totalIncome * 0.8;
        
        if ($totalIncome > 0 && $totalExpense > $budgetLimit) {
            $recommendations[] = (object) [
                'message' => 'Waspada! Pengeluaran Anda sudah melebihi 80% dari pemasukan. Coba batasi pengeluaran tersier.',
                'week_start' => $now->startOfWeek()->format('Y-m-d')
            ];
        } else {
            $recommendations[] = (object) [
                'message' => 'Keuangan Anda bulan ini terlihat sehat. Pertahankan kebiasaan mencatat transaksi!',
                'week_start' => $now->startOfWeek()->format('Y-m-d')
            ];
        }

        // --- RETURN VIEW ---
        return view('dashboard', compact(
            'totalExpense',
            'totalIncome',
            'weeklyAverage',
            'expenseTrend',
            'categoryBreakdown',
            'recentActivities',
            'calendarData',
            'recommendations'
        ));
    }
}