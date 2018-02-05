<?php

namespace Eyewitness\Eye\Repo\Notifications;

use Eyewitness\Eye\Repo\Model;

class Severity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eyewitness_io_notification_severities';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
