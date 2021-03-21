<?php

namespace App\Providers;

use App\Events\emailConfirmation;
use App\Listeners\emailConfirmationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //Event ve Listener tanımladığımız kısım.
        //Hangi event çalıştırıldığında,hangi listener'ın çalıştırılacağını tanımlarız.
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        emailConfirmation::class => [
            emailConfirmationListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
