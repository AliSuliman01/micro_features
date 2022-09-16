<?php


namespace AliSuliman\MicroFeatures\Exceptions;

use AliSuliman\MicroFeatures\Helpers\StatusCode;
use Illuminate\Http\Request;
use Throwable;

class Exception extends \Exception
{
    private $custom_code;
    private $detailed_error;

    public function __construct($message = "", $code = StatusCode::INTERNAL_ERROR, $custom_code = null, $detailed_error = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->custom_code = $custom_code ?? $code;
        $this->detailed_error = $detailed_error;
    }

    public function render(Request $request){
        return response()->json(error($this->message,$this->custom_code, $this->detailed_error), $this->code);
    }
}