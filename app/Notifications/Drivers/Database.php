<?php

namespace Eyewitness\Eye\Notifications\Drivers;

use Exception;
use Eyewitness\Eye\Eye;
use Eyewitness\Eye\Repo\Notifications\History;

class Database implements DriverInterface
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
        try {
            History::create([
                'title' => $message->title(),
                'isError' => $message->isError(),
                'description' => $message->plainDescription(),
                'severity' => $message->severity(),
                'type' => $message->type(),
                'meta' => $message->meta(),
                'acknowledged' => 0,
            ]);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to send Database notification', $e);
        }
    }

}
