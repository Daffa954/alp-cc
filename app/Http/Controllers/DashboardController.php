<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Activity;
// use App\Models\Income; // Uncomment jika Anda sudah membuat model Income

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // --- 1. STATISTIK BULAN INI ---

        // Total Pengeluaran Bulan Ini
        $totalExpense = Expense::where('user_id', $user->id)
            ->whereMonth('date', $now->month) // Menggunakan kolom 'date' sesuai schema Anda
            ->whereYear('date', $now->year)
            ->sum('amount');

        // Total Pemasukan Bulan Ini (Placeholder jika model Income belum ada)
        // Jika sudah ada model Income, ganti 0 dengan: Income::where('user_id', $user->id)->whereMonth('date', $now->month)->sum('amount');
        $totalIncome =  Income::where('user_id', $user->id)->whereMonth('date_received', $now->month)->sum('amount');

        // --- 2. STATISTIK MINGGUAN (Rata-rata Harian) ---

        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        // Total pengeluaran minggu ini
        $weeklyExpense = Expense::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        // Rata-rata harian (Total minggu ini / 7 hari)
        $weeklyAverage = $weeklyExpense / 7;
        $monthlyExpenses = Expense::where('user_id', $user->id)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->get();

        $monthlyActivities = Activity::where('user_id', $user->id)
            ->whereMonth('date_start', $now->month)
            ->whereYear('date_start', $now->year)
            ->get();

        $calendarData = [];

        // foreach ($monthlyExpenses as $exp) {
        //     $date = Carbon::parse($exp->date)->format('Y-m-d');
        //     $calendarData[$date]['expenses'][] = [
        //         'amount' => number_format($exp->amount, 0, ',', '.'),
        //         'category' => $exp->category,
        //         'desc' => $exp->description
        //     ];
        // }
        foreach ($monthlyExpenses as $exp) {
            // Pastikan pakai format 'Y-m-d' (Penting: huruf kecil semua)
            // Ini menjamin hasil "2025-12-05", BUKAN "2025-12-5"
            $date = Carbon::parse($exp->date)->format('Y-m-d');

            $calendarData[$date]['expenses'][] = [
                'amount' => number_format($exp->amount, 0, ',', '.'),
                'category' => $exp->category,
                'desc' => $exp->description
            ];
        }
        foreach ($monthlyActivities as $act) {
            $date = Carbon::parse($act->date_start)->format('Y-m-d');
            $calendarData[$date]['activities'][] = [
                'title' => $act->title,
            ];
        }
        // --- 3. CHART DATA (Expense Trend) ---

        // Kita ambil data 30 hari terakhir untuk grafik
        $chartData = Expense::where('user_id', $user->id)
            ->where('date', '>=', $now->copy()->subDays(30))
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format data agar sesuai dengan Chart.js (Labels & Data)
        $labels = [];
        $data = [];

        // Loop 30 hari terakhir agar grafik tidak bolong jika tidak ada transaksi
        for ($i = 29; $i >= 0; $i--) {
            $dateLabel = $now->copy()->subDays($i)->format('Y-m-d');
            $labels[] = $now->copy()->subDays($i)->format('d M'); // Label sumbu X (Tgl)

            // Cari apakah ada transaksi di tanggal ini
            $dayData = $chartData->firstWhere('date', $dateLabel);
            $data[] = $dayData ? $dayData->total : 0; // Masukkan nominal atau 0
        }

        $expenseTrend = [
            'labels' => $labels,
            'data' => $data
        ];

        // --- 4. CATEGORY BREAKDOWN (Untuk Pie Chart / List) ---

        $categoryBreakdown = Expense::where('user_id', $user->id)
            ->whereMonth('date', $now->month)
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(5) // Ambil 5 kategori terbesar
            ->get()
            ->map(function ($item) {
                // Menambahkan warna & icon dummy untuk UI
                return [
                    'name' => $item->category,
                    'total' => $item->total,
                    'count' => $item->count,
                    'icon' => 'fas fa-tag', // Default icon
                    'color' => '#ff6b00'    // Default color
                ];
            });

        // --- 5. RECENT ACTIVITIES ---

        // Pastikan model Activity ada, jika tidak kosongkan array
        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- 6. SMART RECOMMENDATIONS (Dummy Logic) ---

        $recommendations = [];
        if ($totalExpense > ($totalIncome * 0.8) && $totalIncome > 0) {
            $recommendations[] = (object) [
                'message' => 'Anda telah menggunakan 80% dari pemasukan bulan ini. Pertimbangkan untuk berhemat.',
                'week_start' => $now->startOfWeek()->format('Y-m-d')
            ];
        } else {
            $recommendations[] = (object) [
                'message' => 'Pengeluaran Anda bulan ini cukup stabil. Pertahankan!',
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