<?php

namespace AliSuliman\P2PRpc\Http\Middleware;

use AliSuliman\P2PRpc\Classes\Helpers\StatusCode;
use AliSuliman\P2PRpc\Exceptions\AuthorizationException;
use AliSuliman\P2PRpc\Exceptions\Exception;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class RpcAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->header('Authorization')){

            $token = explode(' ', $request->header('Authorization'))[1] ;

            try {
                $hashKey = file_get_contents(__DIR__ . '/../../storage/jwt-secret.key');

                $decoded = JWT::decode($token,new Key($hashKey,'HS256'));

                $request->json()->add(['client_identity' => $decoded]);

            } catch (\Exception $e){
                throw new Exception($e->getMessage(), StatusCode::UNAUTHORIZED);
            }

            return $next($request);
        }

        throw new AuthorizationException();

    }
}
