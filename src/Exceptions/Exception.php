<?php


namespace AliSuliman\P2PRpc\Exceptions;


use AliSuliman\P2PRpc\Classes\Helpers\Helpers;
use Illuminate\Http\Request;
use Throwable;

class Exception extends \Exception
{
    private $custom_code;
    private $detailed_error;

    public function __construct($message = "", $code = null, $custom_code = null, $detailed_error = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->custom_code = $custom_code ?? $code;
        $this->detailed_error = $detailed_error;
    }

    public function render(Request $request){
        return response()->json(Helpers::error($this->message,$this->custom_code, $this->detailed_error), $this->code);
    }
}