<?php

declare(strict_types=1);

use Cog\Laravel\Clickhouse\Migration\AbstractClickhouseMigration;
use App\Traits\ClickhouseMigrations;

return new class extends AbstractClickhouseMigration {
    use ClickhouseMigrations;

    public function up(): void
    {
        $this->clickhouseClient->write(
            <<<SQL
            CREATE DICTIONARY IF NOT EXISTS exchangers_dictionary
            (
                id UInt64,
                name String
            )
            PRIMARY KEY id
            SOURCE(MYSQL(
                {$this->fromMysqlTable('exchangers')}
            ))
            LAYOUT(FLAT())
            LIFETIME(MIN 300 MAX 600)
            SQL
        );
    }
};
