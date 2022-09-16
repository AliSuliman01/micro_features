<?php


namespace AliSuliman\MicroFeatures\RemoteModels;


use AliSuliman\MicroFeatures\Builders\CacheQueryBuilder;
use AliSuliman\MicroFeatures\Builders\RemoteQueryBuilder;
use AliSuliman\MicroFeatures\Interfaces\ShouldBeCached;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

abstract class RemoteModel
{
    public static $index;

    public function __construct()
    {
        self::$index = static::$index ?? Str::snake(Str::pluralStudly(basename(static::class)));
    }

    public static abstract function originServiceName():string;

    public static function query()
    {
        if ((new static()) instanceof ShouldBeCached){
            if (!Cache::has(self::$index))
                return (new RemoteQueryBuilder(static::originServiceName(),self::$index))->caching();
            return new CacheQueryBuilder(self::$index);
        }
        return new RemoteQueryBuilder(static::originServiceName(),self::$index);
    }
}