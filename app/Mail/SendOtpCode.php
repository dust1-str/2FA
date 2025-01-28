<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Class SendOtpCode
 *
 * This is the mail that is sent when an OTP (One-Time Password) needs to be sent to a user.
 *
 * @package App\Mail
 */
class SendOtpCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The OTP code.
     *
     * @var string
     */
    public $otp;

    /**
     * Create a new message instance.
     *
     * @param string $otp The OTP code to be sent.
     * @return void
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }
    
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Your OTP Code',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.otp',
            with: ['otp' => $this->otp],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}