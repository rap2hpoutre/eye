<?php

namespace Eyewitness\Eye\Repo\History;

use Eyewitness\Eye\Repo\Model;
use Eyewitness\Eye\Repo\Queue as QueueRepo;

class Queue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eyewitness_io_history_queue';

    /**
     * The values to cast.
     *
     * @var array
     */
    protected $casts = ['date' => 'date'];

    /**
     * Get the queue this history belongs to.
     */
    public function queue()
    {
        return $this->belongsTo(QueueRepo::class);
    }
}
