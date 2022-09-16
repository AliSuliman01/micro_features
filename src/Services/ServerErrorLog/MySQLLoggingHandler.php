<?php

namespace AliSuliman\MicroFeatures\Services\ServerErrorLog;

use AliSuliman\MicroFeatures\Model\MicroConfig;
use Illuminate\Support\Facades\Schema;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use AliSuliman\MicroFeatures\Models;

class MySQLLoggingHandler extends AbstractProcessingHandler
{

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $data = [
            'message' => $record['message'],
            'context' => json_encode($record['context']),
            'level' => $record['level'],
            'level_name' => $record['level_name'],
            'channel' => $record['channel'],
            'record_datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'extra' => json_encode($record['extra']),
            'formatted' => $record['formatted'],
            'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => date("Y-m-d H:i:s"),
        ];
        if (Schema::hasTable((new ServerErrorLog())->getTable())) {
            if (ServerErrorLog::query()->count() >= MicroConfig::get('max_size_of_server_error_log')) {
                $last_records = ServerErrorLog::query()
                    ->latest()
                    ->take(intval(MicroConfig::get('min_size_of_server_error_log')))
                    ->get()
                    ->makeHidden(['id'])
                    ->reverse()
                    ->toArray();
                ServerErrorLog::query()->truncate();
                sleep(2);
                ServerErrorLog::query()->insert($last_records);
            }

            ServerErrorLog::query()->insert($data);
        }
    }
}
