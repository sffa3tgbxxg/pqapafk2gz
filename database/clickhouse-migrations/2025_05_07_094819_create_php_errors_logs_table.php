<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;

return new class extends AbstractClickhouseMigration
{
    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE TABLE php_errors_logs (
                error_message String,
                time DateTime
            ) ENGINE = MergeTree()
            ORDER BY (time)
            SQL
        );
    }
};
