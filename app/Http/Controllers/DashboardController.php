<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Activity;
use Illuminate\Http\Request; // Pastikan Request di-import
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Tambahkan parameter Request $request
    public function index(Request $request) 
    {
        $user = Auth::user();
        
        // 1. TANGKAP TANGGAL DARI URL (Agar bisa ganti bulan)
        // Jika tidak ada di URL, default ke bulan sekarang
        $reqMonth = $request->query('month', Carbon::now()->month);
        $reqYear = $request->query('year', Carbon::now()->year);
        
        // Buat object Carbon sebagai 'Konteks Waktu' (Tgl 1 bulan terpilih)
        $dateContext = Carbon::createFromDate($reqYear, $reqMonth, 1);

        // --- 1. STATISTIK BULANAN (Gunakan $dateContext, BUKAN $now) ---
        $totalExpense = Expense::where('user_id', $user->id)
            ->whereMonth('date', $dateContext->month)
            ->whereYear('date', $dateContext->year)
            ->sum('amount');

        $totalIncome = Income::where('user_id', $user->id)
            ->whereMonth('date_received', $dateContext->month)
            ->whereYear('date_received', $dateContext->year)
            ->sum('amount');

        // --- 2. STATISTIK MINGGUAN (Relatif terhadap bulan yang dipilih) ---
        // Kita ambil minggu pertama dari bulan yang dipilih agar relevan
        $startOfWeek = $dateContext->copy()->startOfWeek();
        $endOfWeek = $dateContext->copy()->endOfWeek();

        $weeklyExpense = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        $weeklyAverage = $weeklyExpense / 7;

        // --- 3. DATA UNTUK KALENDER ---
        $calendarData = [];

        // Ambil Data Expense Sesuai Bulan Terpilih
        $monthlyExpenses = Expense::where('user_id', $user->id)
            ->whereMonth('date', $dateContext->month)
            ->whereYear('date', $dateContext->year)
            ->get();

        foreach ($monthlyExpenses as $exp) {
            $dateKey = Carbon::parse($exp->date)->format('Y-m-d');
            
            if (!isset($calendarData[$dateKey])) {
                $calendarData[$dateKey] = [
                    'total_expense' => 0, 'total_income' => 0, 'expenses' => [], 'activities' => []
                ];
            }
            $calendarData[$dateKey]['expenses'][] = [
                'amount' => number_format($exp->amount, 0, ',', '.'),
                'category' => $exp->category,
                'desc' => $exp->description
            ];
            $calendarData[$dateKey]['total_expense'] += $exp->amount;
        }

        // Ambil Data Income Sesuai Bulan Terpilih
        $monthlyIncomes = Income::where('user_id', $user->id)
            ->whereMonth('date_received', $dateContext->month)
            ->whereYear('date_received', $dateContext->year)
            ->get();

        foreach ($monthlyIncomes as $inc) {
            $dateKey = Carbon::parse($inc->date_received)->format('Y-m-d');
            if (!isset($calendarData[$dateKey])) {
                $calendarData[$dateKey] = [
                    'total_expense' => 0, 'total_income' => 0, 'expenses' => [], 'activities' => []
                ];
            }
            $calendarData[$dateKey]['total_income'] += $inc->amount;
        }

        // Ambil Data Activity Sesuai Bulan Terpilih
        $monthlyActivities = Activity::where('user_id', $user->id)
            ->whereMonth('date_start', $dateContext->month)
            ->whereYear('date_start', $dateContext->year)
            ->get();

        foreach ($monthlyActivities as $act) {
            $dateKey = Carbon::parse($act->date_start)->format('Y-m-d');
            if (!isset($calendarData[$dateKey])) {
                $calendarData[$dateKey] = [
                    'total_expense' => 0, 'total_income' => 0, 'expenses' => [], 'activities' => []
                ];
            }
            $calendarData[$dateKey]['activities'][] = ['title' => $act->title];
        }

        // --- 4. DATA GRAFIK (Expense Trend Bulan Terpilih) ---
        // Ubah logika: Tampilkan grafik dari Tanggal 1 s/d Akhir Bulan Terpilih
        $startDate = $dateContext->copy()->startOfMonth();
        $endDate = $dateContext->copy()->endOfMonth();

        $chartData = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->get();

        $labels = [];
        $data = [];
        
        // Loop setiap hari dalam bulan tersebut
        $daysInMonth = $dateContext->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $currentDay = $dateContext->copy()->day($i);
            $dateKey = $currentDay->format('Y-m-d');
            
            $labels[] = $currentDay->format('d'); // Label tanggal saja (1, 2, 3...)
            $dayData = $chartData->firstWhere('date', $dateKey);
            $data[] = $dayData ? $dayData->total : 0;
        }

        $expenseTrend = [
            'labels' => $labels,
            'data' => $data
        ];

        // --- 5. BREAKDOWN KATEGORI ---
        $categoryBreakdown = Expense::where('user_id', $user->id)
            ->whereMonth('date', $dateContext->month) // Gunakan Context
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

        // --- 6. Lain-lain ---
        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recommendations = [];
        $budgetLimit = $totalIncome * 0.8;
        if ($totalIncome > 0 && $totalExpense > $budgetLimit) {
            $recommendations[] = (object) ['message' => 'Waspada! Pengeluaran bulan ini sudah >80% pemasukan.'];
        } else {
            $recommendations[] = (object) ['message' => 'Keuangan bulan ini terlihat sehat.'];
        }

        $balance = $user->balance;

        // Jangan lupa kirim $dateContext ke view
        return view('dashboard', compact(
            'totalExpense', 'totalIncome', 'weeklyAverage', 'expenseTrend',
            'categoryBreakdown', 'recentActivities', 'calendarData',
            'recommendations', 'balance', 'dateContext'
        ));
    }
}