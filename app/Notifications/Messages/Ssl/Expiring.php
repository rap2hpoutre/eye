<?php

namespace Eyewitness\Eye\Notifications\Messages\Ssl;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Expiring extends BaseMessage
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
        return 'Your SSL certifcate is due to expire soon';
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
            'Valid to' => date('Y-m-d', $this->meta['valid_to']),
        ];
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Your SSL certificate is going to expire soon. You can see a full result of the certificate on your Eyewitness dashboard.';
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'SSL';
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
