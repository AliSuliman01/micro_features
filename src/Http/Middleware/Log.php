<?php


namespace AliSuliman\MicroFeatures\Http\Middleware;

use AliSuliman\MicroFeatures\Exceptions\AuthorizationException;
use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\Facades\Auth;
use AliSuliman\MicroFeatures\Helpers\StatusCode;
use AliSuliman\MicroFeatures\Jobs\BroadcastJob;
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
        $jsonResponse = $response->getJson();
        dispatch(new BroadcastJob(new StoreJob('activity_logs',[
            'jsonRequest' => $jsonRequest,
            'jsonResponse' => $jsonResponse,
            'activity_type' => $request->getUri()
        ]),['logs']));

        return $response;
    }
}