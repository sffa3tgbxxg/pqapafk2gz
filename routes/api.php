<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => '/auth'], function () {
    Route::post("/login", [AuthController::class, 'login']);
    Route::post("/register", [AuthController::class, 'register']);
});

Route::group(['prefix' => '/me', 'middleware' => ['auth:sanctum']], function () {
    Route::get("/", [MeController::class, 'index']);
});


Route::group(['prefix' => '/account', 'middleware' => ['auth:sanctum']], function () {
    Route::get("/", [AccountController::class, 'index']);
    Route::get("/{invoiceId}", [AccountController::class, 'show']);
    Route::post("/payment", [AccountController::class, 'payment']);
});
