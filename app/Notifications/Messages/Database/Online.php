<?php

namespace Eyewitness\Eye\Notifications\Messages\Database;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Online extends BaseMessage
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
        return 'Database is online';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Good news - the database "'.e($this->meta['connection']).'" is online and working normally.';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'Good news - the database *'.e($this->meta['connection']).'* is online and working normally.';
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
            'Size' => e($this->meta['size']).'MB',
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
        return $this->getSeverity('high');
    }
}
