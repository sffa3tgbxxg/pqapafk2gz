<?php

namespace App\Services;

use App\Models\Exchanger;
use App\Models\ServiceExchanger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExchangerService
{
    public function getExchangersForService(int $serviceId): Collection
    {
        return Exchanger::query()
            ->whereNotIn('id', function ($query) use ($serviceId) {
                $query->select('exchanger_id')
                    ->from('service_exchangers')
                    ->where('service_id', $serviceId);
            })
            ->where('active', true)
            ->get();
    }

    public function list(string $name = "")
    {
        return Exchanger::query()
            ->when($name, function (Builder $query) use ($name) {
                $query->where('name', $name);
            })
            ->get();
    }

    public function store(array $data): Exchanger
    {
        return DB::transaction(function () use ($data) {
            return Exchanger::query()->create(array_merge($data, [
                'active'=>true,
            ]));
        });
    }

    public function update(int $exchangerId, array $data): void
    {
        DB::transaction(function () use ($data, $exchangerId) {
            return Exchanger::query()
                ->where('id', '=', $exchangerId)
                ->update($data);
        });
    }

    public function show($exchangerId)
    {
        return Exchanger::query()
            ->where('id', '=', $exchangerId)
            ->first();
    }
}
