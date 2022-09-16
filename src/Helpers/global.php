<?php


use AliSuliman\MicroFeatures\Helpers\RPC;

if (!function_exists('success')) {

    function success($data = null): array
    {
        return [
            'success' => true,
            'data' => $data,
        ];
    }
}
if (!function_exists('error')) {

    function error($message = '', $detailed_error = null, $error_code = null): array
    {
        return [
            'success' => false,
            'error_code' => $error_code,
            'message' => $message,
            'detailed_error' => $detailed_error,
        ];
    }
}

if (!function_exists('rpc')) {

    function rpc($serviceName, $index, $method_name, $params = [])
    {

        return (new RPC($serviceName, $index))->call($method_name, $params);
    }
}

if (!function_exists('array_null_filter')) {

    function array_null_filter($array)
    {
        return array_filter($array, function ($item) {
            return $item !== null;
        });
    }
}
