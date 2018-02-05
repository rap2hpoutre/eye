<?php

namespace Eyewitness\Eye\Notifications\Drivers;

use Exception;
use GuzzleHttp\Client;
use Eyewitness\Eye\Eye;

class Pushover implements DriverInterface
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
            'html' => 1,
            'user' => $recipient->address,
            'token' => $recipient->meta['token'],
            'title' => 'Eyewitness.io alert',
            'priority' => ($message->severity() === 'high' && $message->isError()) ? '1' : '0',
            'message' => '<b>'.$message->title().':</b> '.$message->plainDescription()
        ];

        try {
            app(Client::class)->post('https://api.pushover.net/1/messages.json', [
                'form_params' => $body,
                'headers' => $headers
            ]);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to send Slack notification', $e);
        }
    }

}
