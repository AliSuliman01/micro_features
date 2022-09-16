<?php


namespace AliSuliman\MicroFeatures\Exceptions;

use AliSuliman\MicroFeatures\Helpers\StatusCode;
use Illuminate\Http\Request;

class TokenExpiredException extends \Exception
{
    public function render(Request $request)
    {
        return response()->json(error(__('expired_token')),StatusCode::UNAUTHENTICATED);
    }
}
