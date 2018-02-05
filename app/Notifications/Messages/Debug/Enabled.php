<?php

namespace Eyewitness\Eye\Notifications\Messages\Debug;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Enabled extends BaseMessage
{
    /**
     * Is this message an error notification.
     *
     * @return bool
     */
    public function isError()
    {
        return true;
    }

    /**
     * The title of the notification.
     *
     * @return string
     */
    public function title()
    {
        return 'Debug mode has been enabled on your production server';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Warning; debug mode has been enabled on your production server.';
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'Debug';
    }

    /**
     * The seveirty level for this message.
     *
     * @return string
     */
    public function severity()
    {
        return $this->getSeverity('high');
    }
}
