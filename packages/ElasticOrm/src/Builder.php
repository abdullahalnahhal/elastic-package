<?php

namespace ElasticOrm;

use Closure;
use Exception;
use Elastic\Elasticsearch\ClientBuilder;
use Spatie\ElasticsearchQueryBuilder\Queries\Query;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\ExistsQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\WildcardQuery;
use Spatie\ElasticsearchQueryBuilder\Builder as SpatieBuilder;
class Builder
{
    /**
     * array of mapping function to 
     *
     * @var string[]
     */
    private $mapper = [
        "where"   => "must",
        "orWhere" => "should"
    ];

    /**
     * array of 
     *
     * @var string[]
     */
    private $reservedMethods = [
        "get",
        "one",
        "where",
        "orWhere",
        "range",
        "exists",
        "term",
        "whereNot",
        "like",
        "notLike",
    ];

    private $client;
    private $query;

    private $limit = 10;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
        $this->query = $this->newQueryBuilder();
    }

    private function newQueryBuilder(): SpatieBuilder
    {
        return new SpatieBuilder($this->client); 
    }

    private function whereWithArray(array $conditions, bool $isRevert = false): void
    {
        foreach($conditions as $key => $value)
        {
            if(is_string($key)){
                $this->where(column: $key, value: $value, isRevert: $isRevert);
            }else{
                $this->where(column: $value, isRevert: $isRevert);
            }
        }
    }

    private function functionWhere(Closure $function)
    {
        $builder = new ($this);
        $function($builder);
        $query = $builder->getQuery();
        dd($query->getQuery());
        $this->query->addQuery($query);
        dd($query);
    }

    private function range(string $column, string $operator, mixed $value, bool $isRevert = false): RangeQuery
    {
        $query = RangeQuery::create($column);
        if(!in_array($operator, ['<', '>', '<=', '>='])){
            throw new Exception("Operand Not Valid ... !");
        }

        switch ($operator){
            case '<' :
                return $query->lt($value);
                break;
            case '>' :
                return $query->gt($value);
                break;
            case '<=' :
                return $query->lte($value);
                break;
            case '>=' :
                return $query->gte($value);
                break;
        }
    }

    private function exists(string $fieldName, bool $isRevert = false): ExistsQuery
    {
        $query = ExistsQuery::create($fieldName);
       
        return $this;
    }

    private function term(string $column, mixed $value, bool $isRevert = false)
    {
        if(is_object($value)){
            throw new Exception("Value can't be an object ... !");
        }

        if(is_array($value)){
            $query = TermsQuery::create($column, $value);
        }else{
            $query = TermQuery::create($column, $value);
        }
        
        $this->query->addQuery($query, $isRevert ? 'must_not' : "must");

        return $this;
    }

    private function whereNot(Closure|string|array $column, string $operand = "=", mixed $value = null): Builder
    {

        return $this->where($column, $operand, $value, true);
    }

    private function like(string $column, string $value,  bool $isRevert = false): Builder
    {
        $value = str_replace("%", "*", $value);
        $query = WildcardQuery::create($column, $value);
        $this->query->addQuery($query, $isRevert ? 'must_not' : "must");

        return $this;
    }

    private function notLike(string $column, string $value): Builder
    {
        return $this->like($column, $value,  true);
    }

    private function where(Closure|string|array $column, string $operand = "=", mixed $value = null, bool $isRevert = false): Query;
    {
        if(is_a($column, Closure::class)){
            $this->functionWhere($column);
        }

        if(is_string($column)){
            if(is_null($value)){
                $query = $this->exists($column, $isRevert);
            }elseif($operand === '=' || $operand === '!='){
                $query =  $this->term($column, $value, $isRevert);
            }else{
                $query =  $this->range($column, $operand, $value, $isRevert);
            }
        }

        if(is_array($column)){
            $query =  $this->whereWithArray($column, $isRevert);
        }
        
        return $query;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function dd(): void
    {
        dd($this->query->getPayload());
    }

    public function jdd(): void
    {
        dd(json_encode($this->query->getPayload()));
    }

    public function get()
    {
        $this->query->size($this->limit);
        return $this->query->search();
    }

    public function one()
    {
        $this->limit = 1;
        $items = $this->get();
        
        return $items[0] ?? null;
    }

    public function __call(string $methodName, array $arguments)
    {
        if(!in_array($methodName, $this->reservedMethods)){
            throw new Exception("Method Doesn't Exists ... !");
        }

        $query = call_user_func_array([$methodName, $this], $arguments);
        $this->query->addQuery($query);
    }
}
