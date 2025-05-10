<?php

namespace App\Providers;

use App\Services\RabbitMQService;
use Illuminate\Support\ServiceProvider;

class RabbitMQServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('rabbitmq', function ($app) {
            return new RabbitMQService();
        });
    }

    public function boot(): void
    {
        //
    }
}
