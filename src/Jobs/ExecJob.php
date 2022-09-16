<?php


namespace AliSuliman\MicroFeatures\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

class ExecJob extends Job  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $space;
    public $headers;
    public $method;
    public $rpcParams;
    public $accessToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($space, $method, $rpcParams, $accessToken)
    {
        $this->space = $space;
        $this->method = $method;
        $this->rpcParams = $rpcParams;
        $this->accessToken = $accessToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $headers = array();
        $headers[] = 'Content-type: application/json';
        $headers[] = "Authorization: Bearer $this->accessToken";

         Http::asJson()->withHeaders($headers)->post(config('micro_identifier.base_url')."/rpc/$this->space",[
            'jsonrpc' => 2.0,
            'method' => "@$this->method",
            'params' => $this->rpcParams,
            'id' => time(),
            'locale' => App::getLocale()
        ]);
    }
}