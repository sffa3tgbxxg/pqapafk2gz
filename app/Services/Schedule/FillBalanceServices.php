<?php

namespace App\Services\Schedule;

use App\Models\ServiceExchanger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FillBalanceServices
{
    public function init()
    {
        $servicesExchangers = $this->getServices();
        foreach ($servicesExchangers as $serviceExhcanger) {
            $class = "App\\Services\\Methods\\{$serviceExhcanger->exchanger->name}Exchanger";
            if (!class_exists($class) || !$serviceExhcanger->service->hasSubscribe()) {
                continue;
            }
            $balance = (new $class())->getBalance($serviceExhcanger->exchanger->endpoint, $serviceExhcanger->api_key);
            $serviceExhcanger->balance = $balance;
            $serviceExhcanger->save();
        }
    }

    private function getServices(): Collection
    {
        return ServiceExchanger::query()
            ->with('exchanger')
            ->whereHas('exchanger', function (Builder $query) {
                $query->where('active', true);
            })
            ->where('active', true)
            ->get();
    }
}
