<?php

namespace Eyewitness\Eye\App\Witness;

use Illuminate\Support\Facades\Log as LogFacade;
use Eyewitness\Eye\App\Mail\PingEyewitness;
use Illuminate\Support\Facades\Cache;
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
            if ($this->eyewitnessEmailHeartBeat()) {
                if (config('eyewitness.send_queued_emails')) {
                    $this->sendQueuedMail();
                } else {
                    $this->sendImmediateMail();
                }
            }
        } catch (Exception $e) {
            LogFacade::error('Unable to send Eyewitness.io email for token: '.config('eyewitness.app_token').' : '.$e->getMessage());
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

    /**
     * Check if we have recently pinged Eyewitness for email. Only
     * ping if the cache has expired.
     *
     * @return void
     */
    protected function eyewitnessEmailHeartBeat()
    {
        if (Cache::has('eyewitness_email_heartbeat')) {
            return false;
        }

        Cache::add('eyewitness_email_heartbeat', 1, config('eyewitness.email_frequency', 15));

        return true;
    }
}
