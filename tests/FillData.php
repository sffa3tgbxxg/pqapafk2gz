<?php

namespace Tests;

use App\Models\Employee;
use App\Models\Exchanger;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceExchanger;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

trait FillData
{
    private function fillData()
    {
        Artisan::call('db:seed');

        User::query()->insert(
            [
                ['login' => 'admin', 'password' => 'passw0rd'],
                ['login' => 'adminTest', 'password' => 'passw0rd'],
            ]
        );

        Subscriber::query()->create(
            [
                'user_id' => User::query()->where('login', 'admin')->pluck('id')->first(),
                'expiry_at' => now()->addDays(30),
            ]
        );

        Service::query()
            ->create(
                ['name' => 'TestService', 'active' => true, 'api_key' => 'TestService']
            );

        Employee::query()
            ->create(
                ['user_id' => 1, 'service_id' => 1, 'role_id' => Role::getIdByName(Role::OWNER_SERVICE)],
            );

        Exchanger::query()
            ->create(
                [
                    'name' => 'Обмен быстро',
                    'fee' => 18,
                    'auto_withdraw' => true,
                    'min_withdraw' => 10000,
                    'endpoint' => 'http://obmenbistro.ru',
                    'image' => null,
                ]
            );

        Exchanger::query()
            ->create(
                [
                    'name' => 'Обмен медленно',
                    'fee' => 10,
                    'auto_withdraw' => true,
                    'min_withdraw' => 5000,
                    'endpoint' => 'http://obmenslow.ru',
                    'image' => null,
                ]
            );


        ServiceExchanger::query()->create([
            'service_id' => 1,
            'exchanger_id' => 1,
            'api_key' => md5("123"),
            'active' => true,
        ]);
    }
}
