<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyPrice;
use App\Models\Role;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Currency::query()->delete();
        CurrencyPrice::query()->delete();

        DB::statement("ALTER TABLE currencies AUTO_INCREMENT = 0;");

        DB::statement("ALTER TABLE currency_prices AUTO_INCREMENT = 0;");

        DB::statement("ALTER TABLE roles AUTO_INCREMENT = 0;");

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

    }
}
