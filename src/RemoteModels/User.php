<?php


namespace AliSuliman\MicroFeatures\RemoteModels;


class User extends RemoteModel
{
    public static function originServiceName():string
    {
        return 'users';
    }
}