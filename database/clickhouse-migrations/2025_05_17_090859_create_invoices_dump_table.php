<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration
{
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE TABLE invoices_dump (
                id UInt64 ,
                service_id UInt64,
                exchanger_id Nullable(UInt32),
                user_id Nullable(UInt64),
                external_id Nullable(String),
                currency_id UInt8,
                amount_in Decimal(19,8),
                amount_out Decimal(19,8),
                status String,
                comment Nullable(String),
                requisites Nullable(String),
                details Nullable(String),
                expiry_at Nullable(DateTime),
                updated_at DateTime,
                created_at Nullable(DateTime)
                ) 
                ENGINE = ReplacingMergeTree(updated_at)
                PRIMARY KEY (id)
            SQL
        );
    }
};
