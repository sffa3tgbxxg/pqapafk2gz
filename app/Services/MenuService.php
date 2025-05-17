<?php

namespace App\Services;

use App\Models\User;
use App\Policies\AdminPolicy;

class MenuService
{
    public function generate(): array
    {
        $result = [];

        foreach ($this->menu() as $key => $value) {
            if (!empty($value['policies'])) {
                foreach ($value['policies'] as $policyClass) {
                    $policy = app($policyClass);
                    if (!$policy()) {
                        continue 2;
                    }
                }
            }
            unset($value['policies']);
            $result[$key] = $value;
        }
        return $result;
    }

    public function menu(): array
    {
        return [
//            'Home' => [
//                'name' => 'Главная',
//            ],
            'Services' => [
                'name' => 'Сервисы',
            ],
            'ServiceExchangers' => [
                'name' => 'Платежные методы',
            ],
            'Invoices' => [
                'name' => 'Счета',
            ],
            'Employees' => [
                'name' => 'Сотрудники',
            ],
            'Subscription' => [
                'name' => 'Подписка'
            ],
            'Exchangers' => [
                'name' => "Обменники",
                'policies' => [AdminPolicy::class],
            ],
            'Stats' => [
                'name' => "Статистика",
                'pages' => [
                    "ExchangersStats" => [
                        'name' => 'Обменники'
                    ]
                ],
                'policies' => [AdminPolicy::class],
            ],
            'Logs' => [
                'name' => 'Логи',
                'pages' => [
                    'PhpLogs' => [
                        'name' => 'PHP',
                    ],
                    'ApiLogs' => [
                        'name' => 'API',
                    ],
                    'InvoicesLogs' => [
                        'name' => 'Счета',
                    ],
                ],
                'policies' => [AdminPolicy::class]
            ],
            'Withdraws' => [
                'name' => 'Выводы(В разработке)',
            ],
        ];
    }
}
