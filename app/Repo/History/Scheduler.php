<?php

namespace Eyewitness\Eye\Repo\History;

use Eyewitness\Eye\Repo\Model;
use Eyewitness\Eye\Repo\Scheduler as SchedulerRepo;

class Scheduler extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eyewitness_io_history_scheduler';

    /**
     * The values to cast.
     *
     * @var array
     */
    protected $casts = ['expected_completion' => 'datetime',
                        'created_at' => 'datetime'];

    /**
     * Get the scheduler this history belongs to.
     */
    public function scheduler()
    {
        return $this->belongsTo(SchedulerRepo::class);
    }
}
