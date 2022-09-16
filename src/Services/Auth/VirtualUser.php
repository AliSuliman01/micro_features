<?php


namespace AliSuliman\MicroFeatures\Services\Auth;


use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Database\Eloquent\Model;

class VirtualUser extends Model implements UserContract
{
    protected $table = 'virtual_users';

    protected $guarded = [];

    public function getAuthIdentifierName()
    {
        return 'id';
    }
    public function getAuthIdentifier()
    {
        return $this->attributes[$this->getAuthIdentifierName()];
    }
    public function getAuthPassword()
    {
        return $this->attributes['password'];
    }
    public function getRememberToken()
    {
        return $this->attributes[$this->getRememberTokenName()];
    }
    public  function setRememberToken($value)
    {
        $this->attributes[$this->getRememberTokenName()] = $value;
    }
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}