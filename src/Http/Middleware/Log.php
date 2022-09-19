<?php


namespace AliSuliman\MicroFeatures\Http\Middleware;

use AliSuliman\MicroFeatures\Exceptions\AuthorizationException;
use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\Facades\Auth;
use AliSuliman\MicroFeatures\Helpers\StatusCode;
use AliSuliman\MicroFeatures\Helpers\UserAgent;
use AliSuliman\MicroFeatures\Jobs\BroadcastJob;
use AliSuliman\MicroFeatures\Jobs\ExecJob;
use AliSuliman\MicroFeatures\Jobs\StoreJob;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Log
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
        $jsonRequest = $request->getContent();
        $response = $next($request);
        $jsonResponse = $response->getContent();

        if (strlen($jsonRequest) == 0 )
            $jsonRequest = null;

        $hashKey = file_get_contents(__DIR__ . '/../../../storage/jwt-secret.key');

        $accessToken = JWT::encode(Cache::get('serviceInfo'), $hashKey, 'HS256');

        dispatch(new BroadcastJob(new ExecJob('activity_logs','store',
            UserAgent::createUserActivityRequest([
                'jsonRequest' => strlen($jsonRequest) ? $jsonRequest : null,
                'jsonResponse' => strlen($jsonResponse) ? $jsonResponse : null,
            ]),
            $accessToken
        ),['logs']));

        return $response;
    }
}
