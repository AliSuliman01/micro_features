<?php


namespace AliSuliman\MicroFeatures\Http\Traits;

use Illuminate\Support\Collection;

trait UseConditions
{
    public $wheres;
    public $whereIns;
    public $withTrashed = false;

    protected $reverseOperation = [
        '=' => '<>',
        '<>' => '=',
        '>' => '<=',
        '<' => '>=',
        '>=' => '<',
        '<=' => '>',
    ];
    public function where($column, $operation, $value)
    {
        $this->wheres[] = [$column, $operation, $value];
        return $this;
    }
    public function whereIn($column, $array)
    {
        $this->whereIns[] = [$column, $array];
        return $this;
    }

    public function withTrashed($value = true)
    {
        $this->caching = $value;
        return $this;
    }
    public function getQueryConditions()
    {
        return [
            'wheres' => $this->wheres,
            'whereIns' => $this->whereIns,
        ];
    }
    public function setQueryConditions($conditions)
    {
        $this->wheres = $conditions['wheres'];
        $this->whereIns = $conditions['whereIns'];
    }
    public function applyConditions($collection)
    {
        foreach ($this->wheres ?? [] as $where)
            $collection = $collection->where($where[0], $where[1], $where[2]);

        foreach ($this->whereIns ?? [] as $whereIn)
            $collection = $collection->whereIn($whereIn[0], $whereIn[1]);

        return $collection;
    }
    public function applyReverseConditions($collection)
    {
        foreach ($this->wheres ?? [] as $where)
            $collection = $collection->where($where[0], $this->reverseOperation[$where[1]], $where[2]);

        foreach ($this->whereIns ?? [] as $whereIn)
            $collection = $collection->whereNotIn($whereIn[0], $whereIn[1]);

        return $collection;
    }

}