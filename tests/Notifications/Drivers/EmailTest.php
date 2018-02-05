<?php

namespace Eyewitness\Eye\Test\Notifications\Drivers;

use Eyewitness\Eye\Test\TestCase;
use Illuminate\Support\Facades\Mail;
use Eyewitness\Eye\Notifications\Drivers\Email;
use Eyewitness\Eye\Repo\Notifications\Recipient;
use Eyewitness\Eye\Notifications\Messages\TestMessage;

class EmailTest extends TestCase
{
    public function test_sends_email()
    {
        $message = new TestMessage;
        $recipient = new Recipient([
            'type' => 'email',
            'address' => 'test@bob.com'
        ]);

        Mail::shouldReceive('send')->once();

        $email = new Email;
        $email->fire($recipient, $message);
    }
}
