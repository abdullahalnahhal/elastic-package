<?php

namespace ElasticOrm;

use Exception;
use ElasticOrm\Builder;
use Illuminate\Support\Traits\ForwardsCalls;

class Model
{
    use ForwardsCalls;
   
    private $query;

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newQuery()
    {
        $this->query = new Builder();
        return $this->query;
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }
}
