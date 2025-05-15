<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration
{
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE TABLE IF NOT EXISTS invoices_errors_logs (
                invoice_id UInt64,
                error_message String,
                time DateTime
            ) ENGINE = MergeTree()
            ORDER BY (time, invoice_id)
            SQL
        );
    }
};
