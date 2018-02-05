<?php

namespace Eyewitness\Eye\Notifications\Messages\Queue;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class PendingCountOk extends BaseMessage
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
        return 'Your '.e($this->meta['queue']->connection).' ('.e($this->meta['queue']->tube).') has now resolved its pending jobs';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Your queue pending count is now below your set threshold.';
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
            'Your threshold' => e($this->meta['queue']->alert_pending_jobs_greater_than),
            'Actual pending job count' => e($this->meta['pending_job_count']),
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
