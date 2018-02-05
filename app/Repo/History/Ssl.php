<?php

namespace Eyewitness\Eye\Repo\History;

use Eyewitness\Eye\Repo\Model;

class Ssl extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eyewitness_io_history_monitors';

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
        'record' => 'array',
        'created_at' => 'date'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::handleGlobalScope('ssl');
    }
}
