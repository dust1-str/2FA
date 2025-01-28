<?php
namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpCode;
use App\Events\SendOtp;

/**
 * Class SendOtpNotification
 *
 * This listener handles the sending of OTP (One-Time Password) notifications via email.
 *
 * @package App\Listeners
 */
class SendOtpNotification
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
     * @param \App\Events\SendOtp $event The event instance containing the user and OTP.
     * @return void
     */
    public function handle(SendOtp $event)
    {
        Mail::to($event->user->email)->send(new SendOtpCode($event->otp));
    }
}