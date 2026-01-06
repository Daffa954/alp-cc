<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
// Import Model & Observer
use App\Models\Income;
use App\Models\Expense;
use App\Observers\IncomeObserver;
use App\Observers\ExpenseObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mencegah error key length pada MySQL versi lama
        Schema::defaultStringLength(191);

        // Registrasi Observer (Clean & Professional)
        Income::observe(IncomeObserver::class);
        Expense::observe(ExpenseObserver::class);
    }
}