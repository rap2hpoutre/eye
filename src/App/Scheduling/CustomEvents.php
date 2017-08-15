<?php

namespace Eyewitness\Eye\App\Scheduling;

use Illuminate\Console\Scheduling\CallbackEvent as OriginalCallbackEvent;
use Illuminate\Console\Scheduling\Event as OriginalEvent;
use Eyewitness\Eye\App\Scheduling\CallbackEvent;
use Eyewitness\Eye\App\Scheduling\Event;

trait CustomEvents
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

    /**
     * Find the event that matches the mutex.
     *
     * @param  $mutex
     * @return mixd
     */
    protected function findEventMutex($mutex)
    {
        return collect($this->schedule->events())->filter(function ($value) use ($mutex) {
            if (laravel_version_is('<', '5.4.0')) {
                $value = $this->convertEvent($value);
            }

            return $value->mutexName() === $mutex;
        })->first();
    }

    /**
     * Run an event.
     *
     * @param  $event
     * @param  $force
     * @return mixd
     */
    protected function runEvent($event, $force = true)
    {
        $this->convertEvent($event)
             ->ignoreMutex($force)
             ->runInForeground()
             ->run($this->laravel);
    }
}
