<?php

namespace Eyewitness\Eye\Monitors;

use Carbon\Carbon;
use Eyewitness\Eye\Repo\Scheduler as SchedulerRepo;
use Eyewitness\Eye\Scheduling\RunDue;
use Eyewitness\Eye\Repo\History\Scheduler as History;
use Eyewitness\Eye\Scheduling\CustomEvents;
use Illuminate\Console\Scheduling\Schedule;
use Eyewitness\Eye\Notifications\Messages\Scheduler\Missed;
use Eyewitness\Eye\Notifications\Messages\Scheduler\Overdue;

class Scheduler extends BaseMonitor
{
    use CustomEvents, RunDue;

    /**
     * Poll the scheduler for its checks.
     *
     * @return void
     */
    public function poll()
    {
        $this->checkForNewSchedulers();

        foreach ($this->getMissedSchedules() as $scheduler) {
            $this->notifyIfChanges($scheduler);
            $this->setNextSchedule($scheduler);
        }

        foreach ($this->getOverdueSchedules() as $history) {
            $history->output = "No finish ping received by Eyewitness";
            $history->exitcode = 1;
            $history->save();

            if ($history->scheduler->alert_on_fail || ($history->scheduler->alert_run_time_greater_than > 0)) {
                $this->eye->notifier()->alert(new Overdue(['scheduler' => $history->scheduler]));
            }
        }
    }

    /**
     * Get a list of any schedulers that have been missed.
     *
     * @return \Eyewitness\Eye\Repo\Scheduler
     */
    public function getMissedSchedules()
    {
        return SchedulerRepo::where('next_check_due', '<=', Carbon::now())->get();
    }

    /**
     * Get a list of any schedulers that are running and overdue.
     *
     * @return \Eyewitness\Eye\Repo\History\Scheduler
     */
    public function getOverdueSchedules()
    {
        return History::whereNull('exitcode')->where('expected_completion', '<=', Carbon::now())->get();
    }

    /**
     * Check if the status of the scheduler has changed, and send
     * a notification accordingly if needed.
     *
     * @param  \Eyewitness\Eye\Repo\Scheduler  $scheduler
     * @return void
     */
    protected function notifyIfChanges($scheduler)
    {
        if (is_null($scheduler->healthy)) {
            return;
        }

        if ($this->wasUpAndIsNowDown($scheduler)) {
            $scheduler->healthy = false;
            $scheduler->save();

            if ($scheduler->alert_on_missed) {
                $this->eye->notifier()->alert(new Missed(['scheduler' => $scheduler]));
            }
        }
    }

    /**
     * Check if the status of the scheduler has gone down.
     *
     * @param  \Eyewitness\Eye\Repo\Scheduler  $scheduler
     * @return bool
     */
    protected function wasUpAndIsNowDown($scheduler)
    {
        return $scheduler->healthy;
    }

    /**
     * Set when the scheduler is due to run next.
     *
     * @param  \Eyewitness\Eye\Repo\Scheduler  $scheduler
     * @return void
     */
    protected function setNextSchedule($scheduler)
    {
        $scheduler->next_run_due = $this->getNextRunDue($scheduler->schedule, $scheduler->timezone);
        $scheduler->next_check_due = $this->getNextCheckDue($scheduler->schedule, $scheduler->timezone);
        $scheduler->save();
    }

    /**
     * Handle the install process for the Scheduler.
     *
     * @return void
     */
    public function install()
    {
        $this->checkForNewSchedulers();
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
                'schedule' => $e->expression,
                'command' => $e->getCommandName(),
                'timezone' => $e->timezone,
                'mutex' => $e->mutexName(),
                'run_in_background' => $e->runInBackground,
                'without_overlapping' => $e->withoutOverlapping,
                'on_one_server' => $e->onOneServer,
                'next_run_due' => $e->getNextRunDue($e->expression, $e->timezone),
                'next_check_due' => $e->getNextCheckDue($e->expression, $e->timezone)
            ];
        }, $schedule->events());

        return $events;
    }

    /**
     * Check for any new schedulers, and add them to the database.
     *
     * @return void
     */
    public function checkForNewSchedulers()
    {
        $current = SchedulerRepo::all();

        foreach($this->getScheduledEvents() as $event) {
            if (count($current->where('mutex', $event['mutex'])) < 1) {
                $command = new SchedulerRepo;
                $command->fill($event);
                $command->save();
            }
        }
    }
}
