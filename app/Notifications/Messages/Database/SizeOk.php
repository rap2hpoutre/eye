<?php

namespace Eyewitness\Eye\Notifications\Messages\Database;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class SizeOk extends BaseMessage
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
        return 'Database size is now ok';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Good news - the database "'.e($this->meta['connection']).'" size is back to normal limits.';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'Good news - the database *'.e($this->meta['connection']).'* size is back to normal limits.';
    }

    /**
     * Any meta information for the message.
     *
     * @return array
     */
    public function meta()
    {
        return [
            'Connection' => e($this->meta['connection']),
            'Current Size' => e($this->meta['size']).'MB',
            'Minimum size threshold' => e($this->meta['alert_less_than_mb']).'MB',
            'Maximum size threshold' => e($this->meta['alert_greater_than_mb']).'MB',
        ];
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'Database';
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
