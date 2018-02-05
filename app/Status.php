<?php

namespace Eyewitness\Eye;

use Eyewitness\Eye\Eye;
use Eyewitness\Eye\Repo\Statuses;

class Status
{
    /**
     * The eye instance.
     *
     * @var \Eyewitness\Eye\Eye;
     */
    protected $eye;

    /**
     * The current status.
     *
     * @var \Eyewitness\Eye\Repo\Statuses;
     */
    protected $status;

    /**
     * Create a new Status instance.
     *
     * @return void
     */
    public function __construct(Eye $eye)
    {
        $this->eye = $eye;

        $this->status = Statuses::all();
    }

    /**
     * Determine if the given monitor is healthy.
     *
     * @param  string  $monitor
     * @return bool
     */
    public function isHealthy($monitor)
    {
        $result = $this->getStatus($monitor);

        if (is_null($result)) {
            return false;
        }

        return $result->healthy;
    }

    /**
     * Determine if the given monitor is sick.
     *
     * @param  string  $monitor
     * @return bool
     */
    public function isSick($monitor)
    {
        $result = $this->getStatus($monitor);

        if (is_null($result)) {
            return false;
        }

        return (! $result->healthy);
    }

    /**
     * Set a specific monitor as being healthy.
     *
     * @param  string  $monitor
     * @return void
     */
    public function setHealthy($monitor)
    {
        if (! $this->isHealthy($monitor)) {
            $this->setStatus($monitor, true);
        }
    }

    /**
     * Set a specific monitor as being sick.
     *
     * @param  string  $monitor
     * @return void
     */
    public function setSick($monitor)
    {
        if (! $this->isSick($monitor)) {
            $this->setStatus($monitor, false);
        }
    }

    /**
     * Get the status of a specific monitor
     *
     * @param  string  $monitor
     * @return bool|null
     */
    public function getStatus($monitor)
    {
        return $this->status->where('monitor', $monitor)->first();
    }

    /**
     * Set the status of a specific monitor
     *
     * @param  string  $monitor
     * @param  bool  $status
     * @return void
     */
    public function setStatus($monitor, $status)
    {
        Statuses::where('monitor', $monitor)->delete();

        Statuses::create(['monitor' => $monitor,
                          'healthy' => $status]);

        $this->status = Statuses::all();
    }
}
