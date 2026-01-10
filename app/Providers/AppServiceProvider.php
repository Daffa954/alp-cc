<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
// Import Model & Observer
use App\Models\Income;
use App\Models\Expense;
use App\Observers\IncomeObserver;
use App\Observers\ExpenseObserver;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
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

         // Customize verification email
    //    VerifyEmail::toMailUsing(function ($notifiable, $url) {
    //     return (new MailMessage)
    //         ->subject('Verifikasi Email - ' . config('app.name'))
    //         ->view('emails.verify-email', [
    //             'user' => $notifiable,
    //             'verificationUrl' => $url
    //         ]);
    // });

    VerifyEmail::toMailUsing(function ($notifiable, $url) {
        return (new MailMessage)
            ->subject('Verifikasi Email - FinanceHub')
            ->view('emails.verify-email-html', [
                'user' => $notifiable,
                'verificationUrl' => $url
            ]);
    });
    }

}