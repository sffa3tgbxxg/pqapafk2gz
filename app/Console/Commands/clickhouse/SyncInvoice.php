<?php

namespace App\Console\Commands\clickhouse;

use App\Services\AccountService;
use App\Services\Logger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:sync-invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Записывает из invoice';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::connection('clickhouse')->statement(
            'INSERT INTO invoices_dump SELECT * FROM invoices_mysql WHERE updated_at > (SELECT max(updated_at) FROM invoices_dump)'
        );
        $this->info('Invoices synced successfully.');
    }
}
