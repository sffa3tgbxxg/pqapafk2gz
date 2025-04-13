<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Services\ServicesService;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function __construct(private ServicesService $service)
    {

    }

    public function index(Request $request)
    {
        $data = $this->service->list($request->attributes->get('auth_user'));
        return ServiceResource::collection($data);
    }

    public function create()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
