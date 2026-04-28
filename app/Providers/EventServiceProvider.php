<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Verified::class => [
            \App\Listeners\ActivateUserAfterVerification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
