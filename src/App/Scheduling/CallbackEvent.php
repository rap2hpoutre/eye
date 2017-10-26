<?php

namespace Eyewitness\Eye\App\Scheduling;

use Illuminate\Console\Scheduling\CallbackEvent as OriginalCallbackEvent;
use Eyewitness\Eye\App\Scheduling\BaseEventTrait;
use Illuminate\Contracts\Container\Container;
use Eyewitness\Eye\Eye;
use Exception;

class CallbackEvent extends OriginalCallbackEvent
{
    use BaseEventTrait;

    /**
     * Indicates if the command should run in background.
     *
     * @var bool
     */
    public $runInBackground = false;

    /**
     * Create a new custom child event instance.
     * https://stackoverflow.com/a/4722587/1317935
     *
     * @param  \Illuminate\Console\Scheduling\CallbackEvent  $object
     * @return void
     */
    public function __construct(OriginalCallbackEvent $object)
    {
        foreach($object as $property => $value) {
            $this->$property = $value;
        }

        $this->runInBackground = false;

        $this->eye = app(Eye::class);
    }

    /**
     * Run the given event.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return mixed
     *
     * @throws \Exception
     */
    public function run(Container $container)
    {
        if ((! $this->ignoreMutex) && $this->description && $this->withoutOverlapping && (! $this->setMutex())) {
            return;
        }

        $this->runCommandInForeground($container);

        return $this->exitcode;
    }

    /**
     * Run the foreground process.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    protected function runForegroundProcess($container)
    {
        try {
            $container->call($this->callback, $this->parameters);
        } catch (Exception $e) {
            echo(' [Exception] '.$e->getMessage());
            $this->exitcode = 1;
            throw $e;
        }

        $this->exitcode = 0;
    }

    /**
     * Ensure that output is being captured if not already set by the event.
     *
     * @return void
     */
    protected function ensureOutputIsBeingCapturedForEyewitness()
    {
        if (! config('eyewitness.capture_cron_output')) {
            return;
        }

        ob_start();
    }

    /**
     * Get the output from the last scheduled job.
     *
     * @return mixed
     */
    protected function retrieveOutput()
    {
        if (! config('eyewitness.capture_cron_output')) {
            return null;
        }

        $output = ob_get_flush();

        return $output;
    }

    /**
     * Get the mutex name for the scheduled command.
     *
     * @return string
     */
    public function mutexName()
    {
        if (laravel_version_is('<', '5.2.0')) {
            return 'framework/schedule-'.md5($this->description);
        }

        return 'framework/schedule-'.sha1($this->description);
    }
}
