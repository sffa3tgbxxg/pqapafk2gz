<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\RolesResource;
use App\Models\Employee;
use App\Models\Service;
use App\Services\EmployeeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $employeeService)
    {
    }

    public function index(Request $request)
    {
        $services = $request
            ->attributes
            ->get('auth_user')
            ->services();

        $employees = $this->employeeService->getByServices($services);

        return EmployeeResource::collection($employees);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->authorize('storeEmployee', [Service::query()->findOrFail($data['service_id']), $data['role']]);

        $employee = $this->employeeService->create($request->validated());

        return new EmployeeResource($employee);
    }

    public function destroy(Request $request, int $employeeId)
    {
        $this->authorize('destroyEmployee', Service::query()->whereHas('employees', fn(Builder $query) => $query->where('id', $employeeId))->first());
        $this->employeeService->delete($employeeId);
        return response()->json(['message' => "Сотрудник удален"], 202);
    }

    public function update(UpdateRequest $request, int $employeeId)
    {
        $this->authorize('destroyEmployee', Service::query()->whereHas('employees', fn(Builder $query) => $query->where('id', $employeeId))->first());

        $employee = $this->employeeService->update($employeeId, $request->validated());

        return new EmployeeResource($employee);
    }

    public function rolesForEmployee(Request $request)
    {
        $service = Service::query()->findOrFail($request->integer('service_id'));
        $this->authorize('roles', $service);
        $roles = $this->employeeService->getRoles($service->id, $request->attributes->get('auth_user')->id);
        return RolesResource::collection($roles);
    }

}
