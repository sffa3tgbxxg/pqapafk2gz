<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void publish(string $exchange, string $routingKey, string $queue, array $data)
 * @method static void close()
 */
class RabbitMQ extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'rabbitmq';
    }
}
