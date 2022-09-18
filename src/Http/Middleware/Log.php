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
        $jsonRequest = $request->json()->all();
        $response = $next($request);
        $jsonResponse = $response->getContent();

        dispatch(new BroadcastJob(new ExecJob('activity_logs','store',
            UserAgent::createUserActivityRequest([
                'jsonRequest' => $jsonRequest,
                'jsonResponse' => $jsonResponse,
            ]),
            Auth::$rpcToken
        ),['logs']));

        return $response;
    }
}