<?php
namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Events\UserRegistered;

/**
 * Class SendUserRegisteredNotification
 *
 * This listener handles the sending of email verification notifications when a new user registers.
 *
 * @package App\Listeners
 */
class SendUserRegisteredNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\UserRegistered $event The event instance containing the registered user.
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        Mail::to($event->user->email)->send(new VerifyEmail($event->user));
    }
}