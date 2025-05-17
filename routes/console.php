<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:account-invoices')
    ->everyThreeMinutes()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('app:currencies-updater')
    ->everyThreeMinutes()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('app:fill-balance-services')
    ->everyTenMinutes()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('app:account-invoices-check-payment')
    ->everyThreeMinutes()
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('clickhouse:sync-invoice')
    ->everyMinute()
    ->runInBackground()
    ->withoutOverlapping();
