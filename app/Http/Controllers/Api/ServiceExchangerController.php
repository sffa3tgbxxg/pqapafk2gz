<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceExchanger\StoreRequest;
use App\Http\Requests\ServiceExchanger\UpdateRequest;
use App\Http\Resources\ServiceExchangerResource;
use App\Http\Resources\ServiceResource;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceExchanger;
use App\Services\EmployeeService;
use App\Services\ServiceExchangerService;
use App\Services\ServicesService;
use Illuminate\Http\Request;

class ServiceExchangerController extends Controller
{
    public function __construct(private ServiceExchangerService $serviceExchangerService)
    {

    }

    public function index(Request $request)
    {
        $data = $request->validate([
            'payment_method' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
        ]);
        $exchangers = $this->serviceExchangerService->list($data, $request->attributes->get('auth_user')?->id);
        return ServiceExchangerResource::collection($exchangers);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $this->authorize('updateOrCreateExchanger', Service::query()->findOrFail($data['service_id'] ?? null));

        $serviceExchanger = $this->serviceExchangerService->create($data);

        return new ServiceExchangerResource($serviceExchanger);
    }

    public function update(UpdateRequest $request, int $serviceExchangerId)
    {
        $data = $request->validated();

        $this->authorize('updateOrCreateExchanger', ServiceExchanger::query()
            ->with('service')
            ->findOrFail($serviceExchangerId)
            ?->service
        );

        $serviceExchanger = $this->serviceExchangerService->update($serviceExchangerId, $data);
        return new ServiceExchangerResource($serviceExchanger);
    }

    public function destroy(Service $service)
    {
    }
}
