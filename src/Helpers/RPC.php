<?php


namespace AliSuliman\MicroFeatures\Helpers;

use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\RemoteModels\Service;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RPC
{
    private $url;

    public function __construct($serviceName,$index)
    {
        $this->url = Service::getServiceUrl($serviceName) . "/rpc/$index";
    }

    public function call($method, $params = [])
    {
        $requestPayload = [
            'jsonrpc' => 2.0,
            'method' => "@$method",
            'params' => $params + ['serviceInfo' => Cache::get('serviceInfo')],
            'id' => time(),
            'locale' => App::getLocale()
        ];

        $hashKey = file_get_contents(__DIR__ . '/../../storage/jwt-secret.key');

        $accessToken = JWT::encode(Cache::get('serviceInfo'), $hashKey, 'HS256');

        $headers = array();
        $headers[] = 'Content-type: application/json';
        $headers[] = "Authorization: Bearer $accessToken";

        $response = Http::asJson()->withHeaders($headers)->post($this->url,$requestPayload)->onError(function($response){
            throw new Exception($response->getMessage(), $response->getCode());
        });

        if (is_null($response))
            throw new Exception("the response is null from that request : {$this->url}",StatusCode::INTERNAL_ERROR);

        if (property_exists($response, 'result'))
            return $response->result;
        else
            throw new Exception($response->error->data->message);

    }
}