<?php

namespace App\Services\Clickhouse;

use ClickHouseDB\Client;

class ClickhouseClient
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->database(config('clickhouse.connections.default.database', 'paymentswh'));
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
