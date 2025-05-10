<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreRequest;
use App\Http\Requests\Service\UpdateRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Role;
use App\Models\Service;
use App\Services\EmployeeService;
use App\Services\ServicesService;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function __construct(private ServicesService $service, private EmployeeService $employeesService)
    {

    }

    public function index(Request $request)
    {
        $data = $this->service->list($request->boolean('with_limit'), $request->attributes->get('auth_user')?->id);
        return ServiceResource::collection($data);
    }

    public function store(StoreRequest $request)
    {
        $service = $this->service->create($request->validated());
        $this->employeesService->create(
            [
                'service_id' => $service->id,
                'login' => $request->attributes->get('auth_user')?->login,
                'role' => Role::OWNER_SERVICE,
            ]
        );

        return new ServiceResource($service);
    }

    public function update(UpdateRequest $request, Service $service)
    {
        $this->authorize('update', $service);

        $this->service->update($service->id, $request->validated());

        $service->refresh();

        return new ServiceResource($service);
    }

    public function destroy(Service $service)
    {
        $this->service->delete($service->id);

        return response()->json(['message' => 'Success'], 202);
    }
}
