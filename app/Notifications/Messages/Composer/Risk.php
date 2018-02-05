<?php

namespace Eyewitness\Eye\Notifications\Messages\Composer;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Risk extends BaseMessage
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
        return 'Your composer.lock has security risks';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'The latest SensioLabs security check for your Composer.lock file has new security alerts. You should view your application on Eyewitness.io and review the list ASAP.';
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
