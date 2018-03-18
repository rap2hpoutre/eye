<?php

namespace Eyewitness\Eye\Notifications\Messages\Scheduler;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Working extends BaseMessage
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
        return 'Scheduled cron now working';
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
            'Schedule' => e($this->meta['scheduler']->schedule)
        ];
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'The scheduled cron command "'.e($this->meta['scheduler']->command).'" is now working';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'The scheduled cron command *'.e($this->meta['scheduler']->command).'* is now working';
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
