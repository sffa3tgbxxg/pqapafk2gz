<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Support\Collection;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {

    }

    public function register(RegisterRequest $request)
    {
        $token = $this->authService->register($request->validated());
        return new Collection(['token' => $token]);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request->validated());
        return new Collection(['token' => $token]);
    }
}
