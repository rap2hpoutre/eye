<?php

namespace Eyewitness\Eye\Notifications\Messages\Scheduler;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Missed extends BaseMessage
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
        return 'A scheduled cron did not start when expected';
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
            'Last Run' => e($this->meta['scheduler']->latest_ping->created_at->diffForHumans())
        ];
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'The scheduled cron command "'.e($this->meta['scheduler']->command).'" was due to start, but it was missed and not run.';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'The scheduled cron command *'.e($this->meta['scheduler']->command).'* was due to start, but it was missed and not run.';
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
