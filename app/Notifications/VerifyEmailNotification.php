<?php
// app/Notifications/VerifyEmailNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🎯 Verifikasi Email - ' . config('app.name'))
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Selamat datang di **' . config('app.name') . '**')
            ->line('Klik tombol di bawah untuk verifikasi email Anda:')
            ->action('🔐 Verifikasi Email', $verificationUrl)
            ->line('Link berlaku 60 menit')
            ->line('Jika Anda tidak membuat akun, abaikan email ini.')
            ->salutation('Salam, Tim ' . config('app.name'));
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}