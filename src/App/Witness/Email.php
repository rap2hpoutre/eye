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
     * Get all the email checks.
     *
     * @return array
     */
    public function check()
    {
        $data['email'] = $this->getSendHistory();

        return $data;
    }

    /**
     * Get the number of emails sent by the application for the past hour (if tracked).
     *
     * @return array
     */
    public function getSendHistory()
    {
        for ($i=0; $i<2; $i++) {
            $tag = gmdate('Y_m_d_H', time() - (3600*$i));

            $history[$tag] = Cache::get('eyewitness_mail_send_count_'.$tag, 0);
        }

        return $history;
    }

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
     * Increment the email cache counter.
     *
     * @return void
     */
    public function incrementCacheCounter()
    {
        $tag = gmdate('Y_m_d_H');
        Cache::add('eyewitness_mail_send_count_'.$tag, 0, 180);
        Cache::increment('eyewitness_mail_send_count_'.$tag, 1);
    }

    /**
     * Send an queued email to Eyewitness to confirm emails are ok.
     *
     * @return void
     */
    protected function sendQueuedMail()
    {
        if (laravel_version_is('<', '5.4.0')) {
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
