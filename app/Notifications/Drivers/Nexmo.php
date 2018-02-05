<?php

namespace Eyewitness\Eye\Notifications\Drivers;

use Exception;
use GuzzleHttp\Client;
use Eyewitness\Eye\Eye;

class Nexmo implements DriverInterface
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
        ];

        $body = [
            'api_key' => $recipient->meta['api_key'],
            'api_secret' => $recipient->meta['api_secret'],
            'to' => $recipient->address,
            'from' => 'Eyewitness_io',
            'text' => config('app.name', 'Your Application').': '.$message->title()
        ];

        try {
            app(Client::class)->post('https://rest.nexmo.com/sms/json', [
                'json' => $body,
                'headers' => $headers
            ]);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to send Nexmo notification', $e);
        }
    }

}
