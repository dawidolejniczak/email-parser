<?php

namespace App\Mail;

use App\Entities\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BasicMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Mail
     */
    private $content;

    /**
     * BasicMail constructor.
     * @param Mail $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
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
            ->view('mail-views.basic', ['content' => $this->content]);
    }
}
