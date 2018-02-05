<?php

namespace Eyewitness\Eye\Notifications\Messages\Dns;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Change extends BaseMessage
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
        return 'Your DNS records have changed';
    }

    /**
     * Any meta information for the message.
     *
     * @return array
     */
    public function meta()
    {
        return [
            'Domain' => e($this->meta['domain']),
        ];
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'You can see a full list of what changes occured in your DNS records on your application Eyewitness dashboard.';
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'DNS';
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
