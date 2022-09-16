<?php


namespace AliSuliman\MicroFeatures\Builders;


use AliSuliman\MicroFeatures\Http\Traits\UseConditions;

abstract class QueryBuilder
{
    use UseConditions;

    public abstract function first();
    public abstract function get();
    public abstract function store($data);
    public abstract function update($data);
    public abstract function delete();
}