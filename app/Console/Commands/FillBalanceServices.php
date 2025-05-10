<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FillBalanceServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-balance-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет балансы у сервисов каждые 10 минут';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(\App\Services\Schedule\FillBalanceServices::class)->init();
    }
}
