<?php

namespace Eyewitness\Eye\Notifications;

use Eyewitness\Eye\Eye;
use Illuminate\Support\Facades\Cache;
use Eyewitness\Eye\Repo\Notifications\Recipient;
use Eyewitness\Eye\Notifications\Drivers\Database;
use Eyewitness\Eye\Notifications\Messages\MessageInterface;

class Notifier
{
    /**
     * Send the notification with the given message.
     *
     * @param  \Eyewitness\Eye\Notifications\Messages\MessageInterface  $message
     * @return void
     */
    public function alert(MessageInterface $message)
    {
        (new Database)->fire(new Recipient, $message);

        if (Cache::has('eyewitness_debounce_'.$message->safeType().'_'.strtolower(class_basename($message)))) {
            return;
        }

        foreach (Recipient::where($message->severity(), true)->get() as $recipient) {
            $this->sendTo($recipient, $message);
        }

        Cache::add('eyewitness_debounce_'.$message->safeType().'_'.strtolower(class_basename($message)), 1, 1);
    }

    /**
     * Send an alert notification with the given message to a specific recipient.
     *
     * @param  \Eyewitness\Eye\Repo\Notifications\Recipient  $recipient
     * @param  \Eyewitness\Eye\Notifications\Messages\MessageInterface  $message
     * @return void
     */
    public function sendTo(Recipient $recipient, MessageInterface $message)
    {
        $driver = "\\Eyewitness\\Eye\\Notifications\\Drivers\\".ucfirst(strtolower($recipient->type));

        if (class_exists($driver)) {
            $channel = resolve($driver);
            $channel->fire($recipient, $message);
        } else {
            app(Eye::class)->logger()->debug('Notification Driver does not exist', $driver);
        }
    }
}
