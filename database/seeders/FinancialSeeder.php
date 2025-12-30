<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Activity; // Pastikan Model Activity sudah ada
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FinancialSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama agar tidak duplikat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Income::truncate();
        Expense::truncate();
        Activity::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================================
        // USER 1: MAHASISWA (Hemat, Uang Saku, Ojol)
        // ==========================================================
        $mhs = User::create([
            'name' => 'Budi Santoso',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'address' => 'Surabaya',
            'job' => 'Mahasiswa',
            'job_location' => 'Surabaya',
            'balance' => 0,
        ]);

        $this->generateData($mhs, 'mahasiswa');

        // ==========================================================
        // USER 2: FREELANCER (Income Besar tapi Jarang, WFC)
        // ==========================================================
        $freelancer = User::create([
            'name' => 'Siti Designer',
            'email' => 'freelancer@example.com',
            'password' => Hash::make('password'),
            'address' => 'Surabaya',
            'job' => 'Freelance UI/UX Designer',
            'job_location' => 'Remote / Co-working Space',
            'balance' => 0,
        ]);

        $this->generateData($freelancer, 'freelancer');

        // ==========================================================
        // USER 3: KARYAWAN (Gaji Tetap, Tagihan Rutin)
        // ==========================================================
        $karyawan = User::create([
            'name' => 'Pak Andi Manager',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('password'),
            'address' => 'Perumahan Elite Blok A',
            'job' => 'Manager Pemasaran',
            'job_location' => 'Surabaya',
            'balance' => 0,
        ]);

        $this->generateData($karyawan, 'karyawan');
    }

    /**
     * Logic Generator Data
     */
    private function generateData($user, $role)
    {
        $startDate = Carbon::create(2025, 11, 1); // Mulai November 2025
        $endDate = Carbon::create(2025, 12, 31);  // Sampai Desember 2025

        // 1. GENERATE INCOME
        if ($role == 'mahasiswa') {
            // Bulanan
            $this->createIncome($user, 'Kiriman Orang Tua', 2500000, '2025-11-01', true);
            $this->createIncome($user, 'Kiriman Orang Tua', 2500000, '2025-12-01', true);
            // Tambahan
            $this->createIncome($user, 'Hadiah Ulang Tahun', 500000, '2025-12-15', false);
        } 
        elseif ($role == 'freelancer') {
            // Proyekan
            $this->createIncome($user, 'DP Proyek Web', 5000000, '2025-11-10', false);
            $this->createIncome($user, 'Pelunasan Desain Logo', 1500000, '2025-11-25', false);
            $this->createIncome($user, 'Maintenance Fee', 2000000, '2025-12-05', false);
            $this->createIncome($user, 'Proyek Akhir Tahun', 7000000, '2025-12-20', false);
        } 
        elseif ($role == 'karyawan') {
            // Gaji Tetap
            $this->createIncome($user, 'Gaji Pokok November', 12000000, '2025-11-25', true);
            $this->createIncome($user, 'Gaji Pokok Desember', 12000000, '2025-12-25', true);
            $this->createIncome($user, 'Bonus Tahunan', 5000000, '2025-12-20', false);
        }

        // 2. GENERATE EXPENSE & ACTIVITY (Looping Harian)
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            
            // --- LOGIC MAHASISWA ---
            if ($role == 'mahasiswa') {
                // Makan Harian
                $this->createExpense($user, 'Makan Siang (Warteg)', 'Makan & Minum', 15000, $date);
                $this->createExpense($user, 'Makan Malam', 'Makan & Minum', 20000, $date);
                
                // Transportasi (Ada activity)
                if (rand(0, 100) < 60) { // 60% chance kuliah
                    $act = Activity::create([
                        'user_id' => $user->id,
                        'title' => 'Kuliah Sistem Operasi',
                        'distance_in_km' => 5.5,
                        'transportation' => 'Ojek Online',
                        'cost_to_there' => 12000,
                        'activity_location' => 'Kampus Gedung B',
                        'date_start' => $date->copy()->setTime(8, 0),
                        'date_end' => $date->copy()->setTime(12, 0),
                    ]);
                    // Expense terkait activity
                    Expense::create([
                        'user_id' => $user->id,
                        'activity_id' => $act->id,
                        'date' => $date,
                        'category' => 'Transportasi',
                        'description' => 'Ojol Pulang Pergi Kampus',
                        'amount' => 24000
                    ]);
                }
                
                // Utilitas Bulanan
                if ($date->day == 5) {
                    $this->createExpense($user, 'Bayar Listrik Kos', 'Tempat Tinggal', 150000, $date);
                    $this->createExpense($user, 'Paket Data', 'Utilitas', 80000, $date);
                }
            }

            // --- LOGIC FREELANCER ---
            elseif ($role == 'freelancer') {
                // Makan
                $this->createExpense($user, 'Makan Siang', 'Makan & Minum', 35000, $date);

                // WFC (Work From Cafe) - Sering
                if (rand(0, 100) < 50) { 
                    $act = Activity::create([
                        'user_id' => $user->id,
                        'title' => 'Meeting Klien / Coding',
                        'distance_in_km' => 3.0,
                        'transportation' => 'Motor Pribadi',
                        'cost_to_there' => 5000, // Bensin
                        'activity_location' => 'Starbucks / Co-working',
                        'date_start' => $date->copy()->setTime(13, 0),
                        'date_end' => $date->copy()->setTime(17, 0),
                    ]);
                    
                    Expense::create([
                        'user_id' => $user->id,
                        'activity_id' => $act->id,
                        'date' => $date,
                        'category' => 'Makan & Minum', // Kopi masuk F&B
                        'description' => 'Kopi & Snack WFC',
                        'amount' => 65000
                    ]);
                }

                // Langganan Software (Awal Bulan)
                if ($date->day == 1) {
                    $this->createExpense($user, 'Adobe Creative Cloud', 'Langganan', 300000, $date);
                    $this->createExpense($user, 'Hosting Server', 'Langganan', 150000, $date);
                }
            }

            // --- LOGIC KARYAWAN ---
            elseif ($role == 'karyawan') {
                // Makan Siang
                if (!$date->isWeekend()) {
                    $this->createExpense($user, 'Makan Siang Kantor', 'Makan & Minum', 50000, $date);
                    
                    // Commute
                    $act = Activity::create([
                        'user_id' => $user->id,
                        'title' => 'Kerja Harian',
                        'distance_in_km' => 15.0,
                        'transportation' => 'Mobil Pribadi',
                        'cost_to_there' => 30000,
                        'activity_location' => 'Kantor Pusat',
                        'date_start' => $date->copy()->setTime(7, 30),
                        'date_end' => $date->copy()->setTime(17, 0),
                    ]);
                    // Bensin mingguan (tiap senin)
                    if ($date->isMonday()) {
                        Expense::create([
                            'user_id' => $user->id,
                            'activity_id' => $act->id,
                            'date' => $date,
                            'category' => 'Transportasi',
                            'description' => 'Isi Bensin Full Tank',
                            'amount' => 350000
                        ]);
                    }
                } else {
                    // Weekend Hedon
                    $this->createExpense($user, 'Makan Keluarga Weekend', 'Hiburan', 500000, $date);
                    $this->createExpense($user, 'Belanja Mingguan', 'Belanja', 1000000, $date);
                }

                // Tagihan Besar (Awal Bulan)
                if ($date->day == 2) {
                    $this->createExpense($user, 'Cicilan Rumah (KPR)', 'Tempat Tinggal', 4500000, $date);
                    $this->createExpense($user, 'Listrik Rumah', 'Utilitas', 1200000, $date);
                }
            }
        }

        // 3. HITUNG ULANG SALDO
        // Ini opsional jika Anda pakai Observer, tapi Wajib jika tidak, 
        // untuk memastikan data konsisten.
        $totalIncome = Income::where('user_id', $user->id)->sum('amount');
        $totalExpense = Expense::where('user_id', $user->id)->sum('amount');
        
        $user->balance = $totalIncome - $totalExpense;
        $user->save();
    }

    // Helper Pemasukan
    private function createIncome($user, $source, $amount, $dateStr, $isRegular)
    {
        Income::create([
            'user_id' => $user->id,
            'source' => $source,
            'amount' => $amount,
            'date_received' => $dateStr,
            'is_regular' => $isRegular,
            'notes' => 'Generated by Seeder'
        ]);
    }

    // Helper Pengeluaran Simple
    private function createExpense($user, $desc, $cat, $amount, $date)
    {
        Expense::create([
            'user_id' => $user->id,
            'date' => $date->format('Y-m-d'),
            'category' => $cat,
            'description' => $desc,
            'amount' => $amount
        ]);
    }
}