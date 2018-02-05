<?php

namespace Eyewitness\Eye\Notifications\Messages\Queue;

use Eyewitness\Eye\Notifications\Messages\BaseMessage;

class Failed extends BaseMessage
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
        return 'Your '.e($this->meta['connection']).' ('.e($this->meta['tube']).') has a failed job!';
    }

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription()
    {
        return 'Warning - your queue has a failed "'.e($this->meta['job']).'" job. You can log into Eyewitness to view more about the failed job information.';
    }

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription()
    {
        return 'Warning - your queue has a failed *'.e($this->meta['job']).'* job. You can log into Eyewitness to view more about the failed job information.';
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
            'Queue' => e($this->meta['tube']),
            'Job' => e($this->meta['job']),
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
