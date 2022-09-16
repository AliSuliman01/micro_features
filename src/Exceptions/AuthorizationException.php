<?php


namespace AliSuliman\MicroFeatures\Exceptions;

use AliSuliman\MicroFeatures\Helpers\StatusCode;
use Illuminate\Http\Request;

class AuthorizationException extends \Exception
{
    public function render(Request $request){
        throw new Exception('unauthorized',StatusCode::UNAUTHORIZED);
    }
}