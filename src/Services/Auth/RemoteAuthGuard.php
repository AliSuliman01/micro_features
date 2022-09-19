<?php


namespace AliSuliman\MicroFeatures\Services\Auth;


use AliSuliman\MicroFeatures\Exceptions\AuthorizationException;
use AliSuliman\MicroFeatures\Exceptions\TokenExpiredException;
use AliSuliman\MicroFeatures\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class RemoteAuthGuard implements Guard
{
    use GuardHelpers;

    private $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        try {
            $payload = JWT::decode($this->request->bearerToken(), new Key(__DIR__ . '/../../../storage/oauth-public.key', 'RS256'));
        } catch (\Throwable $e) {
            return false;
        }

        if ($payload->exp - time() < 0) {
            return false;
        }

        $user = $this->provider->retrieveByToken('id', $payload->jti);

        if (!$user) {
            return false;
        }

        Auth::setUser($user);
        Auth::setAccessToken($this->request->bearerToken());
        Auth::setJtiToken($payload->jti);
        Auth::setRoles($payload->scopes);

        return $this->user = $user;
    }


    public function validate(array $credentials = [])
    {
        if ($this->provider->validateCredentials($this->provider->retrieveByCredentials($credentials), $credentials))
            return true;
        return false;
    }
}