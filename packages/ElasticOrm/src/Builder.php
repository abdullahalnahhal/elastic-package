<?php

namespace ElasticOrm;

use Closure;
use Exception;
use Elastic\Elasticsearch\ClientBuilder;
use Spatie\ElasticsearchQueryBuilder\Queries\TermQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\RangeQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\TermsQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\ExistsQuery;
use Spatie\ElasticsearchQueryBuilder\Builder as SpatieBuilder;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\WildcardQuery;

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
    ];

    private $client;
    private $query;

    private $limit = 10;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
        $this->query = new SpatieBuilder($this->client);
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

    private function range(string $column, string $operator, mixed $value, bool $isRevert = false)
    {
        $query = RangeQuery::create($column);
        if(!in_array($operator, ['<', '>', '<=', '>='])){
            throw new Exception("Operand Not Valid ... !");
        }

        switch ($operator){
            case '<' :
                $query->lt($value);
                break;
            case '>' :
                $query->gt($value);
                break;
            case '<=' :
                $query->lte($value);
                break;
            case '>=' :
                $query->gte($value);
                break;
        }
        $this->query->addQuery($query, $isRevert ? 'must_not' : "must");

        return $this;
    }

    public function exists(string $fieldName, bool $isRevert = false): Builder
    {
        $query = ExistsQuery::create($fieldName);
        $this->query->addQuery($query, $isRevert ? 'must_not' : "must");
       
        return $this;
    }

    public function term(string $column, mixed $value, bool $isRevert = false)
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

    public function whereNot(Closure|string|array $column, string $operand = "=", mixed $value = null): Builder
    {

        return $this->where($column, $operand, $value, true);
    }

    public function like(string $column, string $value,  bool $isRevert = false): Builder
    {
        $value = str_replace("%", "*", $value);
        $query = WildcardQuery::create($column, $value);
        $this->query->addQuery($query, $isRevert ? 'must_not' : "must");

        return $this;
    }

    public function notLike(string $column, string $value): Builder
    {
        return $this->like($column, $value,  true);
    }

    public function where(Closure|string|array $column, string $operand = "=", mixed $value = null, bool $isRevert = false): Builder
    {
        // if(is_a($column, Closure::class)){

        // }

        if(is_string($column)){
            if(is_null($value)){
                $this->exists($column, $isRevert);
            }elseif($operand === '=' || $operand === '!='){
                $this->term($column, $value, $isRevert);
            }else{
                $this->range($column, $operand, $value, $isRevert);
            }
        }

        if(is_array($column)){
            $this->whereWithArray($column, $isRevert);
        }
        
        return $this;
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
        return $this->query->search();
    }

    public function one()
    {
        $this->query->size(1);
        $items = $this->query->search();
        
        return $items[0] ?? null;
    }

    public function __call(string $methodName, array $arguments)
    {
        if(!in_array($methodName, $this->reservedMethods)){
            throw new Exception("Method Doesn't Exists ... !");
        }
    }
}
