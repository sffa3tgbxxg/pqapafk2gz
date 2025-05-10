<?php

namespace App\Console\Commands;

use App\Services\Currency\CurrencyService;
use App\Services\Logger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurrenciesUpdater extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:currencies-updater';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет курсы валют, по курсу рубля';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::transaction(function () {
                app(CurrencyService::class)->updateCurrencies();
            });

        } catch (\Throwable $e) {
            Logger::error("Не удалось обновить курс валют. Error: " . json_encode([
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]));
        }
    }
}
