<?php

namespace Eyewitness\Eye\Test\Notifications\Messages;

use Eyewitness\Eye\Test\TestCase;
use Eyewitness\Eye\Notifications\Messages\MessageInterface;

class AllMessagesTest extends TestCase
{
    /*
     * There is no need to create a test suite for each individual message, as that would essential
     * just be a spell checking test which is not needed.
     *
     * Instead we will just "new" up each test message to make sure there are no syntax errors in
     * any of the notifications, and that they correctly implement the Message Interface. This means
     * they are working as intended.
     */
    public function test_all_messages()
    {
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\TestMessage);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Scheduler\Fast);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Scheduler\Slow);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Scheduler\Error);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Scheduler\Missed);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Scheduler\Overdue);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Scheduler\Working);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Dns\Change);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Ssl\GradeChange);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Ssl\Expiring);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Ssl\Invalid);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Ssl\Revoked);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Composer\Safe);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Composer\Risk);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Database\Offline);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Database\Online);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Database\SizeOk);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Database\SizeLarge);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Database\SizeSmall);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Debug\Enabled);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Debug\Disabled);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\Failed);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\FailedCountExceeded);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\FailedCountOk);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\Offline);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\Online);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\PendingCountExceeded);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\PendingCountOk);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\WaitLong);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Queue\WaitOk);

        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Custom\Failed);
        $this->isValid(new \Eyewitness\Eye\Notifications\Messages\Custom\Passed);
    }


    protected function isValid(MessageInterface $message)
    {
        // If we are here, then the class has to be implementing the MessageInterface,
        // and it was able to be "newed" up; so we can assertTrue.
        $this->assertTrue(true);
    }
}
