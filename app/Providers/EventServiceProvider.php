<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Events\UserRegistered;
use App\Events\SendOtp;
use App\Listeners\SendOtpNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\SendUserRegisteredNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserRegistered::class => [
            SendUserRegisteredNotification::class,
        ],
        SendOtp::class => [
            SendOtpNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
