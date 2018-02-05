<?php

namespace Eyewitness\Eye\Notifications\Messages\Ssl;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class GradeChange extends BaseMessage
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
        return 'Your SSL grade score has changed';
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
            'Old grade' => e($this->meta['old_grade']),
            'New grade' => e($this->meta['new_grade']),
        ];
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'The score given to your SSL certificate has changed. You can see a full result of the certificate on your Eyewitness dashboard.';
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
