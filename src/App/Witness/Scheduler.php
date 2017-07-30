<?php

namespace Eyewitness\Eye\App\Witness;

use Illuminate\Console\Scheduling\Schedule;

class Scheduler
{
    /**
     * Get a list of all scheduled events and their cron frequency.
     *
     * @return array
     */
    public function getScheduledEvents()
    {
        $schedule = app(Schedule::class);

        $events = array_map(function ($event) {
            return [
                'cron' => $event->expression,
                'command' => $event->command,
                'timezone' => $event->timezone,
            ];
        }, $schedule->events());

        return $events;
    }
}
