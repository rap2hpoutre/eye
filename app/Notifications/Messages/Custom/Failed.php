<?php

namespace Eyewitness\Eye\Notifications\Messages\Custom;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Failed extends BaseMessage
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
        return $this->meta->displayName.' has failed';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Warning - your custom monitor '.$this->meta->displayName.' has failed. You can log into Eyewitness to see more about the error with your moniotr.';
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
