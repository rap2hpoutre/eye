<?php

namespace Eyewitness\Eye\App\Scheduling;

use Illuminate\Contracts\Container\Container;

trait BaseEventTrait
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

    /**
     * Force the command to run regardless of mutex.
     *
     * @var mixed
     */
    public $ignoreMutex = false;

    /**
     * Remember if the event wanted to run in the background.
     *
     * @var mixed
     */
    public $forceRunInForeground = false;

    /**
     * Indicates if the command should run in background.
     *
     * @var bool
     */
    public $runInBackground = false;

    /**
     * The start time of the job.
     *
     * @var float
     */
    public $start;

    /**
     * The id of the job.
     *
     * @var string
     */
    public $jobId;

    /**
     * The exit code of the job.
     *
     * @var string
     */
    public $exitcode;

    /**
     * Generate a job id for this event.
     *
     * @return string
     */
    public function generateJobId()
    {
        return time().'_'.str_random(20);
    }

    /**
     * Calculate run time for this event.
     *
     * @return float
     */
    public function calculateRunTime()
    {
        return round(microtime(true) - $this->start, 4);
    }

    /**
     * Get the summary of the event for display.
     *
     * @return string
     */
    public function getSummaryForDisplay()
    {
        return $this->expression.' : '.$this->getCommandName();
    }

    /**
     * Get the summary of the event for display.
     *
     * @return string
     */
    protected function getCommandName()
    {
        if ($this->command) {
            return $this->command;
        }

        if ($this->description) {
            return $this->description;
        }

        if (isset($this->callback) && is_string($this->callback)) {
            return $this->callback;
        }

        return 'Unnamed Closure';
    }

    /**
     * State that the command should be forced to run regardless of mutex.
     *
     * @param  $force
     * @return $this
     */
    public function ignoreMutex($force = true)
    {
        $this->ignoreMutex = $force;

        return $this;
    }

    /**
     * State that the command should run in the foreground.
     *
     * @return $this
     */
    public function runInForeground()
    {
        $this->forceRunInForeground = true;

        return $this;
    }

    /**
     * Check if output is being captured.
     *
     * @return bool
     */
    protected function outputNotBeingCaptured()
    {
        if (is_null($this->output)) {
            return true;
        }

        if (is_callable([$this, 'getDefaultOutput'])) {
            return $this->output === $this->getDefaultOutput();
        }

        return in_array($this->output, ['NUL', '/dev/null']);
    }

    /**
     * Set everything and send a starting ping of the job to Eyewitness.
     *
     * @return void
     */
    protected function sendStartPing()
    {
        $this->start = microtime(true);
        $this->jobId = $this->generateJobId();
        $this->exitcode = 1;

        $this->eye->api()->sendSchedulerStartPing([
            'job_id' => $this->jobId,
            'command' => $this->getCommandName(),
            'schedule' => $this->expression,
            'timezone' => $this->timezone,
            'background' => $this->runInBackground,
            'overlapping' => $this->withoutOverlapping,
            'mutex' => $this->mutexName(),
        ]);
    }

    /**
     * Send a end ping and results of the of the job to Eyewitness.
     *
     * @return void
     */
    protected function sendFinishPing()
    {
        $this->eye->api()->sendSchedulerFinishPing([
            'job_id' => $this->jobId,
            'command' => $this->getCommandName(),
            'schedule' => $this->expression,
            'timezone' => $this->timezone,
            'background' => $this->runInBackground,
            'overlapping' => $this->withoutOverlapping,
            'mutex' => $this->mutexName(),
            'time' => $this->calculateRunTime(),
            'exitcode' => $this->exitcode,
            'output' => $this->retrieveOutput(),
        ]);
    }

    /**
     * Run the command in the foreground.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    protected function runCommandInForeground(Container $container)
    {
        register_shutdown_function(function () {
            $this->forgetMutex();
        });

        $this->ensureOutputIsBeingCapturedForEyewitness();
        $this->sendStartPing();

        if (laravel_version_is('>=', '5.1.0')) {
            $this->callBeforeCallbacks($container);
        }

        try {
            $this->runForegroundProcess($container);
        } finally {
            $this->callAfterCallbacks($container);
            $this->sendFinishPing();
            $this->forgetMutex();
        }
    }

    /**
     * Get the mutex name for the scheduled command.
     *
     * @return string
     */
    public function mutexName()
    {
        if (laravel_version_is('<', '5.2.0')) {
            return 'framework/schedule-'.md5($this->expression.$this->command);
        }

        return 'framework'.DIRECTORY_SEPARATOR.'schedule-'.sha1($this->expression.$this->command);
    }

    /**
     * Get the mutex path for the scheduled command.
     *
     * @return string
     */
    public function mutexPath()
    {
        return storage_path($this->mutexName());
    }

    /**
     * Run the correct shutdown function based upon the Laravel version.
     *
     * @return void
     */
    protected function forgetMutex()
    {
        if (laravel_version_is('<', '5.4.0')) {
            if (file_exists($this->mutexPath())) {
                unlink($this->mutexPath());
            }
        } elseif (laravel_version_is('<', '5.4.17')) {
            $this->cache->forget($this->mutexName());
        } else {
            $this->mutex->forget($this);
        }
    }


    /**
     * Run the correct mutex based upon the Laravel version.
     *
     * @return bool
     */
    protected function setMutex()
    {
        if (laravel_version_is('<', '5.4.0')) {
            return touch($this->mutexPath());
        } elseif (laravel_version_is('<', '5.4.17')) {
            return $this->cache->add($this->mutexName(), true, 1440);
        }

        return $this->mutex->create($this);
    }
}
