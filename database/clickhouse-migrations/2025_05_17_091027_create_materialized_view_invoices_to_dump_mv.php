<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration
{
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
                CREATE MATERIALIZED VIEW invoices_to_dump_mv
                TO invoices_dump
                AS
                SELECT
                    id,
                    service_id,
                    exchanger_id,
                    user_id,
                    external_id,
                    currency_id,
                    amount_in,
                    amount_out,
                    status,
                    comment,
                    requisites,
                    details,
                    expiry_at,
                    updated_at,
                    created_at
                FROM invoices_mysql
            SQL
        );
    }
};
