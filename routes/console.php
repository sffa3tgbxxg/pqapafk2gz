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
