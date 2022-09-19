<?php


namespace AliSuliman\MicroFeatures\RemoteModels;


use AliSuliman\MicroFeatures\Builders\CacheQueryBuilder;
use AliSuliman\MicroFeatures\Builders\RemoteQueryBuilder;
use AliSuliman\MicroFeatures\Interfaces\ShouldBeCached;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

abstract class RemoteModel
{
    public $index;

    public function __construct()
    {
        $this->index = $this->index ?? Str::snake(Str::pluralStudly(class_basename($this)));
    }

    public static abstract function originServiceName():string;

    public static function query()
    {
        $remoteModel = new static();

        if ((new static()) instanceof ShouldBeCached){
            if (!Cache::has($remoteModel->index))
                return (new RemoteQueryBuilder(static::originServiceName(),$remoteModel->index))->caching();
            return new CacheQueryBuilder($remoteModel->index);
        }

        return new RemoteQueryBuilder(static::originServiceName(),$remoteModel->index);
    }
}