<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exchangers\StoreRequest;
use App\Http\Requests\Exchangers\UpdateRequest;
use App\Http\Resources\ExchangerAdminResource;
use App\Http\Resources\ExchangerResource;
use App\Services\ExchangerService;
use Illuminate\Http\Request;

class ExchangersController extends Controller
{
    public function __construct(private ExchangerService $service)
    {

    }

    public function index(Request $request)
    {
        $exchangers = $this->service->list($request->string("name"));

        return ExchangerAdminResource::collection($exchangers);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $exchanger = $this->service->store($data);

        return new ExchangerAdminResource($exchanger);
    }

    public function update(UpdateRequest $request, int $exchangerId)
    {
        $data = $request->validated();
        $this->service->update($exchangerId, $data);
        $exchanger = $this->service->show($exchangerId);
        return new ExchangerAdminResource($exchanger);
    }

    public function getExchangersForService(Request $request)
    {
        $exchangers = $this->service->getExchangersForService($request->input('service_id'));

        return ExchangerResource::collection($exchangers);
    }
}
