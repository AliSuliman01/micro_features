<?php


namespace AliSuliman\MicroFeatures\RemoteModels;


class OauthAccessToken extends RemoteModel
{
    public static function originServiceName():string
    {
        return 'users';
    }
}