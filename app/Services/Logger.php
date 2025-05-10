<?php

namespace App\Services;

use App\Services\Clickhouse\ClickhouseClient;
use Illuminate\Support\Facades\Log;

class Logger extends Log
{
    public function __construct()
    {
    }

    public static function error(string $message, array $context = []): void
    {
        $clickhouseClient = app(ClickhouseClient::class);
        $client = $clickhouseClient->getClient();
        if (!empty($context)) {
            $message .= " error:" . json_encode($context);
        }

        parent::error($message);
        $client->insert('php_errors_logs', [
            [$message, date('Y-m-d H:i:s')]
        ], ['error_message', 'time']);
    }
}
