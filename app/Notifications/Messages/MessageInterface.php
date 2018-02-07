<?php

namespace Eyewitness\Eye\Notifications\Messages;

interface MessageInterface
{
    /**
     * The message constructor can accept an array of meta data.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data = []);

    /**
     * Is this message an error notification.
     *
     * @return bool
     */
    public function isError();

    /**
     * The title of the notification.
     *
     * @return string
     */
    public function title();

    /**
     * A plain description of the message.
     *
     * @return string
     */
    public function plainDescription();

    /**
     * A markup version of the message.
     *
     * @return string
     */
    public function markupDescription();

    /**
     * Any meta information for the message.
     *
     * @return array
     */
    public function meta();

    /**
     * The notification typee.
     *
     * @return string
     */
    public function type();

    /**
     * The seveirty level for this message.
     *
     * @return string
     */
    public function severity();
}
