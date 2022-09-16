<?php


namespace AliSuliman\MicroFeatures\Model;

use AliSuliman\MicroFeatures\Models\MicroModel;

class MicroConfig extends MicroModel
{
    /**
     * @var self
     */
    protected static $config;

    protected $guarded = [

    ];

    protected $hidden = [
        'caching_logs',
        'created_at',
        'updated_at',
    ];

    public static function set($key, $value)
    {
        self::instance()->update([$key => $value]);
    }

    public static function get($key)
    {
        return self::instance()->$key;
    }

    public static function instance()
    {
        if (self::$config)
            return self::$config;
        return self::$config = (new static())->query()->first();
    }

    public static function clear()
    {
        self::$config = null;
    }
}