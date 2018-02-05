<?php

namespace Eyewitness\Eye\Monitors;

use Exception;
use Carbon\Carbon;
use Cron\CronExpression;
use Eyewitness\Eye\Repo\History\Custom as History;
use Eyewitness\Eye\Tools\BladeDirectives;
use Eyewitness\Eye\Notifications\Messages\Custom\Failed;
use Eyewitness\Eye\Notifications\Messages\Custom\Passed;

abstract class Custom extends BaseMonitor
{
    /**
     * The name of this witness to display on the website and in notifications.
     *
     * @var string
     */
    public $displayName;

    /**
     * The icon to use for this witness on the website.
     *
     * @var string
     */
    protected $icon;

    /**
     * The history of this witness.
     *
     * @var \Eyewitness\Eye\Repo\History\Custom
     */
    protected $history;

    /**
     * The scheduled frequency for this monitor to run its checks.
     *
     * @var string
     */
    public $schedule = '0 * * * *';

    /**
     * An optional value to record the status of the monitor that can
     * be displayed on a graph to show 'trends'.
     *
     * @var integer
     */
    protected $value;

    /**
     * If you need your custom witness to hook into the Laravel boot
     * process - you can do this here. This will be called when the
     * EyewitnessServiceProvider is booting in console mode.
     *
     * @return void
     */
    public function boot()
    {
        // your logic here
    }

    /**
     * If your custom witness fails for the first time (i.e. switches from
     * a healthy status to a sick status) - you can add some additional
     * code you want to run here.
     *
     * Remember: Eyewitness will automatically notify you, so you dont need
     * to duplicate that functionality here.
     *
     * @return void
     */
    public function failing()
    {
        // your logic here
    }

    /**
     * If your custom witness recovers from a sick status - you can add
     * some additional code you want to run here.
     *
     * Remember: Eyewitness will automatically notify you, so you dont need
     * to duplicate that functionality here.
     *
     * @return void
     */
    public function recovering()
    {
        // your logic here
    }

    /**
     * Get a safe string representation of the class name.
     *
     * @return boolean
     */
    public function getSafeName()
    {
        return str_replace('\\', '_', strtolower(get_class($this)));
    }

    /**
     * Record an integer value relating to what you are monitoring. This
     * can be used by Eyewitness to display a graph and do trend analysis
     * etc.
     *
     * @param  int  $value
     * @return void
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the current value.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Return an svg icon for this witness. You can override this function
     * and just return full svg string here to add your own custom svg icon
     * for this witness.
     *
     * @return string
     */
    public function getIcon()
    {
        return (new BladeDirectives)->getIconString($this->icon);
    }

    /**
     * Determine if the witness is due to run now.
     *
     * @return boolean
     */
    public function isDue()
    {
        return CronExpression::factory($this->schedule)->isDue();
    }

    /**
     * Determine when the witness is due to run next.
     *
     * @return \Carbon\Carbon
     */
    public function nextDue()
    {
        return Carbon::instance(CronExpression::factory($this->schedule)->getNextRunDate());
    }

    /**
     * Return an eloquent respresentation of the Witness history.
     *
     * @return \Eyewitness\Eye\Repo\History\Custom
     */
    public function history()
    {
        if (is_null($this->history)) {
            $this->history = History::where('meta', $this->getSafeName())
                                    ->latest()
                                    ->get();
        }

        return $this->history;
    }

    /**
     * Save the custom witness history.
     *
     * @param  bool  $status
     * @return void
     */
    public function saveHistory($status)
    {
        try {
            History::create(['type' => 'custom',
                             'meta' => $this->getSafeName(),
                             'value' => $this->getValue(),
                             'record' => [
                                'status' => $status
                             ],
            ]);
        } catch (Exception $e) {
            $this->eye->logger()->error('Custom witness history save failed', $e, $this->getSafeName());
        }
    }

    /**
     * Check the health of the custom witness.
     *
     * @param  bool  $status
     * @return bool
     */
    public function checkHealth($status)
    {
        if ($status) {
            if ($this->eye->status()->isSick('custom_'.$this->getSafeName())) {
                $this->recovering();
                $this->eye->notifier()->alert(new Passed($this));
            }

            $this->eye->status()->setHealthy('custom_'.$this->getSafeName());

            return true;
        }

        if ($this->eye->status()->isHealthy('custom_'.$this->getSafeName())) {
            $this->failing();
            $this->eye->notifier()->alert(new Failed($this));
        }

        $this->eye->status()->setSick('custom_'.$this->getSafeName());

        return false;
    }

    /**
     * Run your checks. Return "true" for a pass and "false" for a fail. Any
     * any uncaught exceptions will also be treated as a fail.
     *
     * @return boolean
     */
    abstract public function run();
}
