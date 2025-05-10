<?php

namespace App\Services;

use App\Models\ServiceExchanger;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ServiceExchangerService
{
    public function list(array $data, int $userId = 0): Collection
    {
        return ServiceExchanger::query()
            ->when($data['payment_method'] ?? null, fn(Builder $query) => $query->whereHas('exchanger', fn(Builder $query) => $query->whereLike('name', "%{$data['payment_method']}%")))
            ->whereHas('service', function (Builder $query) use ($userId, $data) {
                $query->whereHas('employees', function (Builder $query) use ($userId, $data) {
                    $query
                        ->where('user_id', $userId)
                        ->when($data['service'] ?? null, fn(Builder $query) => $query->whereLike('name', "%{$data['service']}%"));
                });
            })
            ->with(['service', 'exchanger'])
            ->get();
    }

    public function create(array $data): ServiceExchanger
    {
        return DB::transaction(function () use ($data) {
            return ServiceExchanger::query()->create([
                'service_id' => $data['service_id'],
                'exchanger_id' => $data['exchanger_id'],
                'api_key' => $data['api_key'],
                'active' => true,
            ]);
        });
    }

    public function update(int $serviceExchangerId, array $data): ServiceExchanger
    {
        return DB::transaction(function () use ($serviceExchangerId, $data) {
            $serviceExchanger = ServiceExchanger::query()->findOrFail($serviceExchangerId);
            $serviceExchanger->update($data);
            return $serviceExchanger->refresh();
        });
    }
}
