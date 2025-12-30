<?php

namespace App\Observers;

use App\Models\Income;
use Illuminate\Support\Facades\Log;

/**
 * IncomeObserver
 * * Menangani sinkronisasi otomatis antara transaksi Pemasukan
 * dengan Saldo Utama (User Balance).
 */
class IncomeObserver
{
    /**
     * Handle the Income "created" event.
     */
    public function created(Income $income): void
    {
        // Pemasukan bertambah -> Saldo bertambah
        $income->user()->increment('balance', $income->amount);
        Log::info("Balance Updated: +{$income->amount} (Income ID: {$income->id})");
    }

    /**
     * Handle the Income "updated" event.
     */
    public function updated(Income $income): void
    {
        // Jika nominal berubah, sesuaikan selisihnya
        if ($income->isDirty('amount')) {
            $originalAmount = $income->getOriginal('amount');
            $newAmount = $income->amount;
            $difference = $newAmount - $originalAmount;

            // Jika positif (nambah), saldo nambah. Jika negatif, saldo kurang.
            if ($difference != 0) {
                $income->user()->increment('balance', $difference);
            }
        }
    }

    /**
     * Handle the Income "deleted" event.
     */
    public function deleted(Income $income): void
    {
        // Pemasukan dihapus -> Saldo berkurang (Revert)
        $income->user()->decrement('balance', $income->amount);
    }
}