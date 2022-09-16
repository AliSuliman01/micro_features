<?php

namespace AliSuliman\MicroFeatures\Builders;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CacheQueryBuilder extends QueryBuilder
{
    private $index;

    public function __construct($index)
    {
        $this->index = $index;
    }

    public function first()
    {
        $collection = collect(Cache::get($this->index));
        return $this->applyConditions(collect($collection))->first();
    }

    public function get()
    {
        $collection = collect(Cache::get($this->index));
        return $this->applyConditions(collect($collection));
    }
}