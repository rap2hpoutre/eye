<?php

namespace Eyewitness\Eye\Repo\History\Scope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class TypeLegacy implements ScopeInterface
{
    /**
     * The type we are scoping.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new type scope.
     *
     * @param  string  $type
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where('type', $this->type);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return void
     */
    public function remove(Builder $builder, Model $model)
    {
        //
    }
}
