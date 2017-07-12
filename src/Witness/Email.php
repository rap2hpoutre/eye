<?php

namespace Eyewitness\Eye\Witness;

use Eyewitness\Eye\Mail\PingEyewitness;
use Illuminate\Support\Facades\Log as LogFacade;
use Illuminate\Support\Facades\Mail;
use Exception;

class Email
{
    /**
     * Try to send an email to Eyewitness to confirm emails are ok.
     *
     * @return void
     */
    public function send()
    {
        try {
            if (config('eyewitness.send_queued_emails')) {
                $this->sendQueuedMail();
            } else {
                $this->sendImmediateMail();
            }
        } catch (Exception $e) {
            LogFacade::error('Unable to send Eyewitness.io email: '.$e->getMessage());
        }
    }

    /**
     * Send an queued email to Eyewitness to confirm emails are ok.
     *
     * @return void
     */
    protected function sendQueuedMail()
    {
        if (laravel_version_less_than_or_equal_to(5.3)) {
            Mail::queue('eyewitness::email', ['timestamp' => time()], function ($message) {
                $message->to(config('eyewitness.app_token').'@eyew.io', 'Eyewitness.io');
                $message->subject('Ping Eyewitness');
            });
        } else {
            Mail::to(config('eyewitness.app_token').'@eyew.io')->queue(new PingEyewitness());
        }
    }

    /**
     * Send an immediate email to Eyewitness to confirm emails are ok.
     *
     * @return void
     */
    protected function sendImmediateMail()
    {
        Mail::send('eyewitness::email', ['timestamp' => time()], function ($message) {
            $message->to(config('eyewitness.app_token').'@eyew.io', 'Eyewitness.io');
            $message->subject('Ping Eyewitness');
        });
    }
}
