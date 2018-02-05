<?php

namespace Eyewitness\Eye\Notifications\Drivers;

use Exception;
use Eyewitness\Eye\Eye;
use Illuminate\Support\Facades\Mail;

class Email implements DriverInterface
{
    /**
     * Fire a notification.
     *
     * @param  \Eyewitness\Eye\Repo\Notifications\Recipient  $recipient
     * @param  \Eyewitness\Eye\Notifications\Messages\MessageInterface  $message
     * @return void
     */
    public function fire($recipient, $message)
    {
        // because we cant use "$message" on Laravel emails
        $msg = $message;

        try {
            Mail::send('eyewitness::email.notification', ['recipient' => $recipient, 'msg' => $msg], function ($m) use ($recipient, $msg) {
                $m->to($recipient->address);
                $m->subject($msg->title());
            });
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to send Email noitification', $e);
        }
    }

}
