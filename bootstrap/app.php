<?php

use App\Http\Middleware\LoadUserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

ini_set('serialize_precision', -1);
ini_set('precision', -1);


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api()->append(LoadUserMiddleware::class);

        $middleware->alias([
            'service-subscribe' => \App\Http\Middleware\ServiceSubscribeMiddleware::class,
            'service-access' => \App\Http\Middleware\ServiceAccessMiddleware::class,
            'access-invoice-user' => \App\Http\Middleware\AccessInvoiceUserMiddleware::class,
            'is-admin-user' => \App\Http\Middleware\IsAdminMiddleware::class,
            'admin-or-subscribe-or-role-in-service' => \App\Http\Middleware\SubscriptionOrRoleInServiceMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
