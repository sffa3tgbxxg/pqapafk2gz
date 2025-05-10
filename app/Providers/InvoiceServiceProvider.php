<?php

namespace App\Providers;

use App\Services\InvoiceService;
use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('invoicefacade', function ($app) {
            return new InvoiceService();
        });
    }

    public function boot(): void
    {
        //
    }
}
