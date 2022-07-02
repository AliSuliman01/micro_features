<?php


namespace AliSuliman\P2PRpc\Exceptions;


use AliSuliman\P2PRpc\Classes\Helpers\Helpers;
use AliSuliman\P2PRpc\Classes\Helpers\StatusCode;
use Illuminate\Http\Request;
use Throwable;

class AuthorizationException extends \Exception
{
    public function render(Request $request){
        throw new Exception('unauthorized',StatusCode::UNAUTHORIZED);
    }
}