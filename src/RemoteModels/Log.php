<?php


namespace AliSuliman\MicroFeatures\RemoteModels;


class Log extends RemoteModel
{
    public static function originServiceName():string
    {
        return 'logs';
    }
}