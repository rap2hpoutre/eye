<?php

namespace Eyewitness\Eye\Repo;

use Eyewitness\Eye\Eye;
use Illuminate\Database\Eloquent\Builder;
use Eyewitness\Eye\Repo\History\Scope\TypeLegacy;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('eyewitness.eyewitness_database_connection');

        parent::__construct($attributes);
    }

    /**
     * Add a global scope to the model. But we have to handle the different
     * scenario, as Laravel 5.1 has a different way compared to all other
     * versions.
     *
     * @param  string  $type
     * @return void
     */

    protected static function handleGlobalScope($type)
    {
        if (Eye::laravelVersionIs('>=', '5.2.0')) {
            static::addGlobalScope('type', function (Builder $builder) use ($type) {
                $builder->where('type', $type);
            });
        } else {
            static::addGlobalScope(new TypeLegacy($type));
        }

    }
}
