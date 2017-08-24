<?php

namespace Eyewitness\Eye\App\Witness;

use Eyewitness\Eye\App\Scheduling\CustomEvents;
use Illuminate\Console\Scheduling\Schedule;

class Scheduler
{
    use CustomEvents;

    /**
     * Get all the scheduler checks.
     *
     * @return array
     */
    public function check()
    {
        return $this->getScheduledEvents();
    }
    /**
     * Get a list of all scheduled events and their cron frequency.
     *
     * @return array
     */
    public function getScheduledEvents()
    {
        $schedule = app(Schedule::class);

        $events = array_map(function ($event) {
            $e = $this->convertEvent($event);
            return [
                'cron' => $event->expression,
                'command' => $event->command,
                'timezone' => $event->timezone,
                'mutex' => $event->mutexName(),
                'background' => $event->runInBackground,
                'overlapping' => $event->withoutOverlapping];
        }, $schedule->events());

        return $events;
    }
}
