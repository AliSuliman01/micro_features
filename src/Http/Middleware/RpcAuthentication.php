<?php


namespace AliSuliman\MicroFeatures\Http\Middleware;

use AliSuliman\MicroFeatures\Exceptions\AuthorizationException;
use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\Facades\Auth;
use AliSuliman\MicroFeatures\Helpers\StatusCode;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class RpcAuthentication
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($request->header('Authorization')) {
            $token = $request->bearerToken();
            try {
                $hashKey = file_get_contents(__DIR__ . '/../../../storage/jwt-secret.key');
                JWT::decode($token, new Key($hashKey, 'HS256'));
            } catch (\Exception $e) {
                throw new Exception($e->getMessage(), StatusCode::UNAUTHENTICATED);
            }

            Auth::setRpcToken($token);
            return $next($request);
        }

        throw new AuthorizationException();
    }
}