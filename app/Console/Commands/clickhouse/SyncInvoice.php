<?php

namespace App\Console\Commands\clickhouse;

use App\Services\AccountService;
use App\Services\Clickhouse\ClickhouseClient;
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
        app(ClickhouseClient::class)->getClient()->wrte('INSERT INTO invoices_dump SELECT * FROM invoices_mysql WHERE updated_at > (SELECT max(updated_at) FROM invoices_dump)');
        Log::info("таблица click invoices_dump заполнена");
    }
}
