<?php


namespace AliSuliman\MicroFeatures\Services\ServerErrorLog;

use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class MySQLLogger
{
    public function __invoke()
    {
        $logger = new Logger("MySQLLogger");
        return $logger->pushHandler(new MySQLLoggingHandler());
    }

    public static function report(\Throwable $e)
    {
        Log::channel('mysql')->error($e->getMessage(),[
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
            'trace' => $e->getTrace(),
        ]);
    }
}
