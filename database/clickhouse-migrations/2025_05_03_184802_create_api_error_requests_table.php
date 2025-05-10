<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration
{
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE TABLE api_error_requests (
                invoice_id UInt64,
                exchanger_id UInt32,
                error_message String,
                time DateTime
            ) ENGINE = MergeTree()
            ORDER BY (time, invoice_id, exchanger_id)
            SQL
        );
    }
};
