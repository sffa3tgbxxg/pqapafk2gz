<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function index(Request $request)
    {
        return new UserResource($request->attributes->get('auth_user'));
    }
}
