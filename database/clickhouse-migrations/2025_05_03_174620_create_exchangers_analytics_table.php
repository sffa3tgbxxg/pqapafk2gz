<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration {
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE TABLE exchangers_analytics (
                invoice_id UInt64,
                exchanger_id UInt64,
                status String,
                request_duration_ms Float64,
                amount Decimal(19, 8),
                record_date Date,
                time DateTime,
            ) ENGINE = MergeTree()
            ORDER BY (record_date, exchanger_id, invoice_id, status)
            SQL
        );
    }

    public function down(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
                DROP TABLE if EXISTS invoices
            SQL
        );
    }
};
