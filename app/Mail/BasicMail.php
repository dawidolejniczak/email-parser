<?php

namespace App\Mail;

use App\Entities\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class BasicMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Mail
     */
    private $mail;

    /**
     * BasicMail constructor.
     * @param Mail $mail
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(env('MAIL_FROM'))
            ->view('mail-views.basic', ['mail' => $this->mail]);
    }
}
