<?php

namespace Eyewitness\Eye\Notifications\Messages;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class TestMessage extends BaseMessage
{
    /**
     * Is this message an error notification.
     *
     * @return bool
     */
    public function isError()
    {
        return false;
    }

    /**
     * The title of the notification.
     *
     * @return string
     */
    public function title()
    {
        return 'Test Notification';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'This is a manually generated notification to check your channel is working correctly.';
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'Test Notification';
    }

    /**
     * The seveirty level for this message.
     *
     * @return string
     */
    public function severity()
    {
        return $this->getSeverity('low');
    }
}
