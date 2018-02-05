<?php

namespace Eyewitness\Eye\Notifications\Messages\Queue;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class WaitLong extends BaseMessage
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
        return 'Your '.e($this->meta['queue']->connection).' ('.e($this->meta['queue']->tube).') jobs are waiting too long!';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Warning - your queue job waiting time is above your set threshold. You can log into Eyewitness to view more about the waiting job information.';
    }

    /**
     * Any meta information for the message.
     *
     * @return array
     */
    public function meta()
    {
        return [
            'Connection' => e($this->meta['queue']->connection),
            'Queue' => e($this->meta['queue']->tube),
            'Driver' => e($this->meta['queue']->driver),
            'Your threshold' => e($this->meta['queue']->alert_wait_time_greater_than).'s',
            'Actual job wait time' => e($this->meta['queue']->current_wait_time).'s',
        ];
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'Queue';
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
