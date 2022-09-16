<?php


namespace AliSuliman\MicroFeatures\Services\Auth;

use AliSuliman\MicroFeatures\RemoteModels\OauthAccessToken;
use AliSuliman\MicroFeatures\RemoteModels\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class RemoteUserProvider implements UserProvider
{
    public function retrieveById($identifier): UserContract
    {
        return new VirtualUser((array)User::query()->where('id','=',$identifier)->first());
    }

    public function retrieveByToken($identifier, $token): ?UserContract
    {

        $retrievedModel = OauthAccessToken::query()->where($identifier,'=',$token)->first();

        return $retrievedModel && !$retrievedModel->revoked
            ? new VirtualUser((array)User::query()->where('id','=',$retrievedModel->user_id)->first()) : null;
    }

    public function updateRememberToken(UserContract $user, $token)
    {
        User::query()
            ->where('id','=',$user->getAuthIdentifier())
            ->update([$user->getRememberTokenName() => $token]);
    }

    public function retrieveByCredentials(array $credentials): ?UserContract
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains($key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return null;
        }

        $query = User::query();
        foreach ($credentials as $key => $value) {
            if (is_array($value) || $value instanceof Arrayable) {
                $query->whereIn($key, $value);
            } elseif ($value instanceof \Closure) {
                $value($query);
            } else {
                $query->where($key,'=', $value);
            }
        }
        return new VirtualUser((array)$query->first());
    }

    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        $plain = $credentials['password'];

        return Hash::check($plain, $user->getAuthPassword());
    }

}