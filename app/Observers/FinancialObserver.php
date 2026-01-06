<?php

namespace App\Observers;

use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class FinancialObserver
{
    // =========================================================================
    // HANDLE INCOME (Pemasukan)
    // =========================================================================
    
    // Saat Pemasukan Baru Dibuat
    public function created_income(Income $income)
    {
        $income->user->increment('balance', $income->amount);
    }

    // Saat Pemasukan Dihapus
    public function deleted_income(Income $income)
    {
        $income->user->decrement('balance', $income->amount);
    }

    // Saat Pemasukan Diedit (Misal: Typo nominal)
    public function updated_income(Income $income)
    {
        // Cek jika amount berubah
        if ($income->isDirty('amount')) {
            $selisih = $income->amount - $income->getOriginal('amount');
            // Jika selisih positif (nambah), saldo nambah. Jika negatif, saldo kurang.
            $income->user->increment('balance', $selisih);
        }
    }

    // =========================================================================
    // HANDLE EXPENSE (Pengeluaran)
    // =========================================================================

    // Saat Pengeluaran Baru Dibuat
    public function created_expense(Expense $expense)
    {
        $expense->user->decrement('balance', $expense->amount);
    }

    // Saat Pengeluaran Dihapus (Uang balik)
    public function deleted_expense(Expense $expense)
    {
        $expense->user->increment('balance', $expense->amount);
    }

    // Saat Pengeluaran Diedit
    public function updated_expense(Expense $expense)
    {
        if ($expense->isDirty('amount')) {
            $selisih = $expense->amount - $expense->getOriginal('amount');
            // Logika terbalik: Jika expense naik, saldo harus TURUN
            $expense->user->decrement('balance', $selisih);
        }
    }
}