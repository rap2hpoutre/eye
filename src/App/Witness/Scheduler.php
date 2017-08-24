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
                'cron' => $e->expression,
                'command' => $e->command,
                'timezone' => $e->timezone,
                'mutex' => $e->mutexName(),
                'background' => $e->runInBackground,
                'overlapping' => $e->withoutOverlapping];
        }, $schedule->events());

        return $events;
    }
}
