<?php

namespace App\Console\Commands;

use App\Services\AccountService;
use App\Services\Logger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:account-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Меняет статус на отменено у инвойсов по оплате подписки';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::transaction(function () {
                app(AccountService::class)->canceledExpired();
            });

        } catch (\Throwable $e) {
            Logger::error("Не удалось отменить счета по подписке в кроне. Error: " . json_encode([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]));
        }
    }
}
