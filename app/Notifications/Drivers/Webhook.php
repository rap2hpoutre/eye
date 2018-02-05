<?php

namespace Eyewitness\Eye\Notifications\Drivers;

use Exception;
use GuzzleHttp\Client;
use Eyewitness\Eye\Eye;

class Webhook implements DriverInterface
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
        $headers = [
            'connect_timeout' => 30,
            'timeout' => 30,
            'verify' => false,
            'debug' => false,
            'Content-Type' => 'application/json'
        ];

        $body = [
            'name' => config('app.name', 'Laravel App'),
            'error' => $message->isError(),
            'type' => $message->type(),
            'title' => $message->title(),
            'description' => $message->plainDescription(),
            'meta' => $message->meta()
        ];

        try {
            app(Client::class)->post($recipient->address, [
                'body' => json_encode($body),
                'headers' => $headers
            ]);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to send Webhook notification', $e);
        }
    }

}
