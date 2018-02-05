<?php

namespace Eyewitness\Eye\Notifications\Drivers;

use Exception;
use GuzzleHttp\Client;
use Eyewitness\Eye\Eye;

class Hipchat implements DriverInterface
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
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$recipient->meta['token'],
        ];

        $body = [
            'color' => $message->isError() ? 'red' : 'green',
            'notify' => ($message->severity() === 'high' && $message->isError()) ? true : false,
            'from' => 'Eyewitness.io',
            'message_format' => 'html',
            'message' => '<b>'.config('app.name', 'Your Application').': '.$message->title().'</b><br/>'.$message->markupDescription(),
        ];

        try {
            app(Client::class)->post('https://api.hipchat.com/v2/room/'.$recipient->address.'/notification', [
                'body' => json_encode($body),
                'headers' => $headers
            ]);
        } catch (Exception $e) {
            app(Eye::class)->logger()->error('Unable to send Hipchat notification', $e);
        }
    }

}
