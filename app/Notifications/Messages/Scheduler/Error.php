<?php

namespace Eyewitness\Eye\Notifications\Messages\Scheduler;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Error extends BaseMessage
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
        return 'A scheduled cron exited with an error';
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
            'Exit Code' => e($this->meta['exitcode'])
        ];
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'The scheduled cron command "'.e($this->meta['scheduler']->command).'" has exited with an error';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'The scheduled cron command *'.e($this->meta['scheduler']->command).'* has exited with an error';
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
