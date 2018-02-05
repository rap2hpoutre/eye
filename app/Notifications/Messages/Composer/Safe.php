<?php

namespace Eyewitness\Eye\Notifications\Messages\Composer;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Safe extends BaseMessage
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
        return 'Your composer.lock is now safe from known risks';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'The latest SensioLabs security check for your Composer.lock file shows no known security issues.';
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'Composer';
    }

    /**
     * The seveirty level for this message.
     *
     * @return string
     */
    public function severity()
    {
        return $this->getSeverity('medium');
    }
}
