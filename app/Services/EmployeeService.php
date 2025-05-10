<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function create(array $data): Employee
    {
        $userNeedle = User::query()->where('login', $data['login'])->firstOrFail();
        unset($data['login']);
        $data['user_id'] = $userNeedle->id;
        $data['role_id'] = Role::getIdByName($data['role']);
        unset($data['role']);

        return DB::transaction(function () use ($data) {
            return Employee::query()->create($data);
        });
    }

    public function getByServices(Collection $services): Collection
    {
        return Employee::query()
            ->with('role')
            ->orderBy('id')
            ->whereIn('service_id', $services->pluck('id'))
            ->get();
    }

    public function delete(int $employeeId): void
    {
        DB::transaction(function () use ($employeeId) {
            Employee::query()
                ->where('id', $employeeId)
                ->delete();
        });
    }

    public function update(int $employeeId, array $data): Employee
    {
        $data['role_id'] = Role::getIdByName($data['role']);
        unset($data['role']);
        unset($data['id']);
        return DB::transaction(function () use ($employeeId, $data) {
            Employee::query()
                ->where('id', $employeeId)
                ->first()
                ->update($data);

            return Employee::query()->find($employeeId);
        });
    }

    public function getRoles(int $serviceId, int $userId): array
    {
        $service = Service::query()->findOrFail($serviceId);
        $role = $service->role($userId)->nameOrig();
        if ($role == Role::OWNER_SERVICE) {
            return [
                [
                    'name_code' => Role::ADMIN_SERVICE,
                    'name' => Role::getByName(Role::ADMIN_SERVICE)
                ],
                [
                    'name_code' => Role::OPERATOR_SERVICE,
                    'name' => Role::getByName(Role::OPERATOR_SERVICE)
                ],
            ];
        } else if ($role == Role::ADMIN_SERVICE) {
            return [
                [
                    'name_code' => Role::OPERATOR_SERVICE,
                    'name' => Role::getByName(Role::OPERATOR_SERVICE)
                ]
            ];
        }
        return [];
    }

    public function canEditOrDelete(int $employeeId): bool
    {
        $user = auth()->user();
        $employee = Employee::query()
            ->with('role')
            ->findOrFail($employeeId);

        $employeeUser = Employee::query()
            ->with('role')
            ->where('user_id', $user->id)
            ->where('service_id', $employee->service_id)
            ->firstOrFail();

        if ($employee->role->nameOrig() == Role::OWNER_SERVICE) {
            return false;
        }

        if (($employeeUser->role->nameOrig() == Role::OWNER_SERVICE)) {
            return true;
        }

        if (($employeeUser->role->nameOrig() == Role::ADMIN_SERVICE) && in_array($employee->role->nameOrig(), [Role::OPERATOR_SERVICE])) {
            return true;
        }

        return false;
    }
}
