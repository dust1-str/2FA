<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use App\Models\User;

/**
 * Class SendOtp
 *
 * This event is triggered when an OTP (One-Time Password) needs to be sent to a user.
 *
 * @package App\Events
 */
class SendOtp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The OTP code.
     *
     * @var string
     */
    public $otp;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User $user The user to whom the OTP is being sent.
     * @param string $otp The OTP code to be sent.
     * @return void
     */
    public function __construct(User $user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}