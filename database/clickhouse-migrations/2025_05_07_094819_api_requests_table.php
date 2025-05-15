<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration
{
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE TABLE IF NOT EXISTS api_requests (
                invoice_id UInt64,
                exchanger_id UInt64,
                status_code Int32,
                endpoint String,
                params String,
                response String,
                time DateTime
            ) ENGINE = MergeTree()
            ORDER BY (time)
            SQL
        );
    }
};
