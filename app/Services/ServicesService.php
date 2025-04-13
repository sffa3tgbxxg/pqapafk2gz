<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ServicesService
{
    public function __construct()
    {

    }

    public function list(int $userId = 0): LengthAwarePaginator
    {
        return Service::query()
            ->when($userId, function (Builder $builder) use ($userId) {
                $builder->whereHas('employees', function (Builder $builder) use ($userId) {
                    $builder->where('user_id', $userId);
                });
            })
            ->with('exchangers')
            ->paginate(20);
    }

    public function create(int $userId, array $data): Service
    {
        return DB::transaction(function () use ($userId, $data) {
            $service = Service::query()
                ->create(array_merge($data, ['active' => true]));

            return $service;
        });
    }

    public function update($data)
    {

    }

    public function delete($data)
    {

    }
}
