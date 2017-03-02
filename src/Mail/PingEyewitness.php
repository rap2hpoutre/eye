<?php

namespace Eyewitness\Eye\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PingEyewitness extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The timestamp the email was generated.
     *
     * @var timestamp
     */
    public $timestamp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->timestamp = time();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('eyewitness::email');
    }
}
