<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyPrice;
use App\Models\EmployeePanel;
use App\Models\Exchanger;
use App\Models\PriceSubscription;
use App\Models\Role;
use App\Models\Subscriber;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Random\RandomException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Currency::query()->delete();
        CurrencyPrice::query()->delete();

        DB::statement("ALTER TABLE currencies AUTO_INCREMENT = 0;");

        DB::statement("ALTER TABLE currency_prices AUTO_INCREMENT = 0;");

        DB::statement("ALTER TABLE roles AUTO_INCREMENT = 0;");

        User::query()->create(
            ['login' => 'admin', 'password' => '12345678']
        );

        User::query()->create(
            ['login' => 'admin_service', 'password' => '12345678']
        );

        User::query()->create(
            ['login' => 'operator_service', 'password' => '12345678']
        );

        User::query()->create(
            ['login' => 'operator_service_2', 'password' => '12345678']
        );


        Subscriber::query()
            ->create(
                ['user_id' => 1, 'expiry_at' => now()->addMonths(1)]
            );

        Currency::query()
            ->insert(
                [
                    ['id' => 1, 'name' => 'Рубли', 'code' => 'RUB', 'symbol' => '₽', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 2, 'name' => 'Доллары', 'code' => 'USD', 'symbol' => '$', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 3, 'name' => 'Биткоин', 'code' => 'BITCOIN', 'symbol' => '₿', 'created_at' => now(), 'updated_at' => now()],
                ]
            );

        CurrencyPrice::query()
            ->insert(
                [
                    ['id' => 1, 'currency_id' => 2, 'price' => 86.5, 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 2, 'currency_id' => 3, 'price' => 7074153, 'created_at' => now(), 'updated_at' => now()],
                ]
            );

        Role::query()
            ->insert(
                [
                    ['id' => 1, 'name' => 'owner_project'],
                    ['id' => 2, 'name' => 'owner_service'],
                    ['id' => 3, 'name' => 'admin_service'],
                    ['id' => 4, 'name' => 'operator_service'],
                ]
            );

        EmployeePanel::query()->create(
            ['user_id' => 1, 'role_id' => 1]
        );


        Exchanger::query()
            ->create(
                [
                    'name' => 'Тестовый обменник',
                    'fee' => 18,
                    'auto_withdraw' => true,
                    'min_withdraw' => 10000,
                    'endpoint' => 'http://testendpoint.ru',
                    'image' => null,
                    'active' => true
                ]
            );

        Exchanger::query()
            ->insert(
                [
                    [
                        'name' => 'Greengo',
                        'fee' => 12,
                        'auto_withdraw' => false,
                        'min_withdraw' => 0,
                        'min_amount' => 1000,
                        'max_amount' => 99999999,
                        'endpoint' => 'https://api.greengo.cc',
                        'image' => null,
                        'active' => true,
                    ],
                    [
                        'name' => 'LuckyPay',
                        'fee' => 15,
                        'min_amount' => 1000,
                        'max_amount' => 99999999,
                        'auto_withdraw' => true,
                        'min_withdraw' => 2000,
                        'endpoint' => 'https://luckypay.io',
                        'image' => null,
                        'active' => true,
                    ],
                    [
                        'name' => 'Bitloga',
                        'fee' => 15,
                        'min_amount' => 1000,
                        'max_amount' => 99999999,
                        'auto_withdraw' => true,
                        'min_withdraw' => 2000,
                        'endpoint' => 'https://bitloga.com',
                        'image' => null,
                        'active' => true,
                    ]
                ]
            );

        PriceSubscription::query()->create(['price_rub' => 30000]);

    }
}
