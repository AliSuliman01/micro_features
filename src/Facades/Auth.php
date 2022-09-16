<?php


namespace AliSuliman\MicroFeatures\Facades;


class Auth extends \Illuminate\Support\Facades\Auth
{
    public static $roles ;
    public static $currentRoleId ;
    public static $jtiToken ;
    public static $accessToken ;
    public static $rpcToken ;

    public static function roles()
    {
        return self::$roles;
    }

    public static function setRoles($roles)
    {
        self::$roles = $roles;
    }

    public static function jtiToken()
    {
        return self::$jtiToken;
    }

    public static function accessToken()
    {
        return self::$accessToken;
    }

    public static function setJtiToken($jtiToken)
    {
        self::$jtiToken = $jtiToken;
    }

    public static function setAccessToken($accessToken)
    {
        self::$accessToken = $accessToken;
    }

    public static function setRpcToken($rpcToken)
    {
        self::$rpcToken = $rpcToken;
    }
}