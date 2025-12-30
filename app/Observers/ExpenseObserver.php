<?php

namespace App\Observers;

use App\Models\Expense;
use Illuminate\Support\Facades\Log;

/**
 * ExpenseObserver
 * * Menangani sinkronisasi otomatis antara transaksi Pengeluaran
 * dengan Saldo Utama (User Balance).
 */
class ExpenseObserver
{
    /**
     * Handle the Expense "created" event.
     */
    public function created(Expense $expense): void
    {
        // Pengeluaran bertambah -> Saldo BERKURANG
        $expense->user()->decrement('balance', $expense->amount);
        Log::info("Balance Updated: -{$expense->amount} (Expense ID: {$expense->id})");
    }

    /**
     * Handle the Expense "updated" event.
     */
    public function updated(Expense $expense): void
    {
        if ($expense->isDirty('amount')) {
            $originalAmount = $expense->getOriginal('amount');
            $newAmount = $expense->amount;
            $difference = $newAmount - $originalAmount;

            // Logika terbalik: Jika expense naik (positif), saldo user harus turun
            if ($difference != 0) {
                $expense->user()->decrement('balance', $difference);
            }
        }
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(Expense $expense): void
    {
        // Pengeluaran dihapus -> Uang kembali (Saldo bertambah)
        $expense->user()->increment('balance', $expense->amount);
    }
}