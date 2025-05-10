<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(private MenuService $service)
    {

    }

    public function index(Request $request)
    {
        return new MenuResource($this->service->generate());
    }
}
