<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Auth\Access\Response;

class ServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Service $service): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Service $service): bool
    {
        $role = $service->role($user->id)?->nameOrig();
        return in_array($role, [Role::OWNER_SERVICE, Role::ADMIN_SERVICE]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Service $service): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Service $service): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Service $service): bool
    {
        return true;
    }

    public function updateOrCreateExchanger(User $user, Service $service): bool
    {
        $role = $service->role($user->id)?->nameOrig();
        return in_array($role, [Role::OWNER_SERVICE, Role::ADMIN_SERVICE, Role::OPERATOR_SERVICE]);
    }

    public function roles(User $user, Service $service): bool
    {
        $role = $service->role($user->id)?->nameOrig();
        return in_array($role, [Role::OWNER_SERVICE, Role::ADMIN_SERVICE]);
    }

    public function storeEmployee(User $user, Service $service, string $role): bool
    {
        $roles = app(EmployeeService::class)->getRoles($service->id, $user->id);
        $roles = array_map(function ($role) {
            return $role['name_code'];
        }, $roles);

        return in_array($role, $roles);
    }

    public function destroyEmployee(User $user, Service $service): bool
    {
        return $this->roles($user, $service);
    }
}
