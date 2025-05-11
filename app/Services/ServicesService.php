<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServicesService
{
    public function __construct()
    {

    }

    public function list(bool $limit, int $userId = 0): LengthAwarePaginator|Collection
    {
        $services = Service::query()
            ->when($userId, function (Builder $builder) use ($userId) {
                $builder->whereHas('employees', function (Builder $builder) use ($userId) {
                    $builder->where('user_id', $userId);
                });
            })
            ->with('exchangers');

        return $limit ? $services->paginate(300) : $services->get();
    }

    public function create(array $data): Service
    {
        return DB::transaction(function () use ($data) {
            return Service::query()
                ->create(array_merge(
                    $data,
                    [
                        'active' => false,
                        'api_key' => md5($data['name'] . time() . random_int(1000, 9999))
                    ]
                ));
        });
    }

    public function update(int $serviceId, array $data): void
    {
        DB::beginTransaction();
        try {
            $service = Service::query()->findOrFail($serviceId);

            $service->update($data);
            Cache::forget("service-api-key:{$service->api_key}");

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }
    }

    public function delete(int $serviceId): bool
    {
        return DB::transaction(function () use ($serviceId) {
            return Service::query()->findOrFail($serviceId)->delete();
        });
    }
}
