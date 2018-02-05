<?php

namespace Eyewitness\Eye\Repo;

use Eyewitness\Eye\Repo\Model;

class Statuses extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eyewitness_io_statuses';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'healthy' => 'bool',
        'updated_at' => 'date'
    ];
}
