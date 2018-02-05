<?php

namespace Eyewitness\Eye\Notifications\Messages\Database;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Offline extends BaseMessage
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
        return 'Database is offline!';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Warning - the database "'.e($this->meta['connection']).'" is offline and not working.';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'Warning - the database *'.e($this->meta['connection']).'* is offline and not working.';
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
