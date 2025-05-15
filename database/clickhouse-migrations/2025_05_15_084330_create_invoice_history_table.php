<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration
{
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE TABLE IF NOT EXISTS invoice_history (
                invoice_id UInt64,
                status String,
                updated_by String,
                user_id Nullable(UInt64),
                details Nullable(String),
                time DateTime
            ) ENGINE = MergeTree()
            ORDER BY (time)
            SQL
        );
    }
};
