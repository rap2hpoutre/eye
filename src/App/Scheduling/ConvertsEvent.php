<?php

namespace Eyewitness\Eye\App\Scheduling;

use Illuminate\Console\Scheduling\CallbackEvent as OriginalCallbackEvent;
use Illuminate\Console\Scheduling\Event as OriginalEvent;
use Eyewitness\Eye\App\Scheduling\CallbackEvent;
use Eyewitness\Eye\App\Scheduling\Event;

trait ConvertsEvent
{
    /**
     * Try and convert the current event object to a custom one.
     * https://stackoverflow.com/a/4722587/1317935
     *
     * @param  $event
     * @return mixed
     */
    protected function convertEvent($event)
    {
        if ($event instanceof OriginalCallbackEvent) {
            return new CallbackEvent($event);
        }

        if ($event instanceof OriginalEvent) {
            return new Event($event);
        }

        return $event;
    }
}
