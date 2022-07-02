<?php


namespace AliSuliman\P2PRpc\Classes\Helpers;


trait Helpers
{
    public static function success($data = null): array
    {
        $response = ['isSuccessful' => true];
        $response['hasContent'] = $data ? true : false;
        $response['code'] = 200;
        $response['message'] = null;
        $response['detailed_error'] = null;
        $response['data'] = $data;
        return $response;
    }

    public static function error($message = '',  $error_code = null, $detailed_error = null): array
    {
        $response = ['isSuccessful' => false];
        $response['code'] = $error_code;
        $response['hasContent'] = false;
        $response['message'] = $message;
        $response['detailed_error'] = $detailed_error;
        $response['data'] = null;
        return $response;
    }
}