<?php

namespace App\Services;

use App\Exceptions\ErrorException;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * @param array $data
     * @return string
     */
    public function register(array $data): string
    {
        return DB::transaction(function () use ($data) {
            try {
                $user = User::query()->create($data);
                return $user->createToken('PaymentServiceApp')->plainTextToken;
            }catch (\Exception $e){
                throw new AuthenticationException();
            }
        });
    }


    public function login(array $data): string
    {
        $user = User::query()->where('login', $data['login'])->first();
        if (!$user) {
            throw new ErrorException("Неправильный логин или пароль", 400);
        }
        return $user->createToken('PaymentServiceApp')->plainTextToken;
    }

}
