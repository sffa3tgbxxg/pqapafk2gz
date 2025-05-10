<?php

namespace App\Console\Commands;

use App\Services\AccountService;
use App\Services\Logger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AccountInvoiceCheckPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:account-invoices-check-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет статус счета на оплату подписки в апи Blockchain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            app(AccountService::class)->initPaymentCheck();
        } catch (\Throwable $e) {
            Logger::error("Не удалось обновить счета по подписке в кроне. Error: " . json_encode([
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]));
        }
    }
}
