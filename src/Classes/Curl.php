<?php


namespace AliSuliman\P2PRpc\Classes;


use AliSuliman\P2PRpc\Classes\Helpers\StatusCode;
use AliSuliman\P2PRpc\Exceptions\Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\App;

class Curl
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    private static function init_curl()
    {
        $hashKey = file_get_contents(__DIR__ . '/../../storage/jwt-secret.key');

        $accessToken = JWT::encode(config('p2p_rpc.identity'), $hashKey, 'HS256');

        $ch = curl_init();

        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = "Authorization: Bearer $accessToken";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        return $ch;
    }

    public function call($method, $params = [])
    {
        $ch = self::init_curl();

        $id = time();

        $requestPayload = [
            'jsonrpc' => 2.0,
            'method' => $method,
            'params' => $params ,
            'id' => $id,
            'locale' => App::getLocale()
        ];

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestPayload));


        try {
            $response = curl_exec($ch);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } finally {
            curl_close($ch);
        }

        $decoded_response = json_decode($response);

        if (is_null($decoded_response))
            throw new Exception("the response is null from that request : {$this->url}",StatusCode::INTERNAL_ERROR);

        if (property_exists($decoded_response, 'result'))
            return $decoded_response->result;
        else
            throw new Exception($decoded_response->error->data->message);

    }
}