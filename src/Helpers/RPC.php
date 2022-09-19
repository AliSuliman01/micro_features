<?php


namespace AliSuliman\MicroFeatures\Helpers;

use AliSuliman\MicroFeatures\Exceptions\Exception;
use AliSuliman\MicroFeatures\RemoteModels\Service;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Client\Response;
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
            'jsonrpc' => "2.0",
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

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestPayload));


        try {
            $response = curl_exec($ch);
        } catch (\Exception $e) {
            throw new GeneralException($e->getMessage());
        } finally {
            curl_close($ch);
        }


        $response = json_decode($response);

        if (is_null($response))
            throw new Exception("the response is null from that request : {$this->url}",StatusCode::INTERNAL_ERROR);


        if (property_exists($response, 'result'))
            return $response->result;
        else if (property_exists($response, 'error'))
            throw new Exception(json_encode($response));
        else
            return $response;

    }
}
