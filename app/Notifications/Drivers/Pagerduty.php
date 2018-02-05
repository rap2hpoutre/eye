<?php

namespace Eyewitness\Eye\Notifications\Drivers;

use Exception;
use GuzzleHttp\Client;
use Eyewitness\Eye\Eye;

class Pagerduty implements DriverInterface
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
            'service_key' => $recipient->address,
            'event_type' => 'trigger',
            'description' => $message->title().': '.$message->plainDescription(),
            'details' => $message->meta()
        ];

        try {
            app(Client::class)->post('https://events.pagerduty.com/generic/2010-04-15/create_event.json', [
                'body' => json_encode($body),
                'headers' => $headers
            ]);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to send Pagerduty notification', $e);
        }
    }

}
