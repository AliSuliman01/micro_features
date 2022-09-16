<?php


namespace AliSuliman\MicroFeatures\RemoteModels;


class FcmToken extends RemoteModel
{
    public static function originServiceName():string
    {
        return 'notifications';
    }
}