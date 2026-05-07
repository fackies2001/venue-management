<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Event\Login;
use Illuminate\Support\Facades\Event;

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
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Email Address – Venue Management System')
                ->view('emails.custom-verify-email', [
                    'url' => $url,
                    'user' => $notifiable,
                ]);
        });


        Event::listen(Login::class, function ($event) {
            $event->user->update([
                'last_login_at' => now(),
            ]);
        });
    }
}
