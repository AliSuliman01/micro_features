<?php


namespace AliSuliman\MicroFeatures\Http\Procedures;

use AliSuliman\MicroFeatures\Facades\Auth;
use AliSuliman\MicroFeatures\Jobs\ExecJob;
use Illuminate\Http\Request;

class QueueProcedure extends Procedure
{
    public function push(Request $request)
    {
        $job = new $request->job_class();
        $job->setProps($request->job_params);
        dispatch($job);
    }

    public function exec(Request $request)
    {
        $params = $request->json('params') + [
                "serviceInfo" => [
                    'name' => $request->json('serviceInfo')['name'],
                    'url' => $request->json('serviceInfo')['url']
                ]
            ];

        return dispatch(new ExecJob($request->json('index'), $request->json('method'), $params, Auth::$rpcToken));
    }
}
