<?php

namespace App\Providers;

use App\Repositories\clickhouse\InvoiceHistoryRepository;
use App\Services\InvoiceService;
use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('invoicefacade', function ($app) {
            return new InvoiceService(
                app(InvoiceHistoryRepository::class),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
