<?php

namespace Eyewitness\Eye\Notifications\Messages\Custom;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Passed extends BaseMessage
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
        return $this->meta->displayName.' is now passing again';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Good news - your custom monitor '.$this->meta->displayName.' is passing again.';
    }

    /**
     * Any meta information for the message.
     *
     * @return array
     */
    public function meta()
    {
        return [
            'Value' => e($this->meta->getValue()),
        ];
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'Custom';
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
