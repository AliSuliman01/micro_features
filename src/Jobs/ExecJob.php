<?php


namespace AliSuliman\MicroFeatures\Jobs;


use AliSuliman\MicroFeatures\Exceptions\Exception;
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
    public $method;
    public $rpcParams;
    public $accessToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($space = null, $method = null, $rpcParams = null, $accessToken = null)
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

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, config('app.url')."/rpc/$this->space");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'jsonrpc' => "2.0",
            'method' => "@$this->method",
            'params' => $this->rpcParams,
            'id' => time(),
            'locale' => App::getLocale()
        ]));


        try {
            $response = curl_exec($ch);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            curl_close($ch);
        }
    }
}
