<?php

namespace Eyewitness\Eye\Notifications\Messages\Scheduler;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Fast extends BaseMessage
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
        return 'A scheduled cron was too fast';
    }

    /**
     * Any meta information for the message.
     *
     * @return array
     */
    public function meta()
    {
        return [
            'Command' => e($this->meta['scheduler']->command),
            'Schedule' => e($this->meta['scheduler']->schedule),
            'Last Run' => e($this->meta['scheduler']->latest_ping->created_at->diffForHumans()),
            'Your alert threshold' => e($this->meta['scheduler']->alert_run_time_greater_than).' seconds',
            'Actual cron job run time' => e(round($this->meta['time_to_run'], 1)).' seconds',
        ];
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'The scheduled cron command "'.e($this->meta['scheduler']->command).'" ran too fast and might imply something went wrong';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'The scheduled cron command *'.e($this->meta['scheduler']->command).'* ran too fast and might imply something went wrong';
    }

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type()
    {
        return 'Scheduler';
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
