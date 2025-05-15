<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ExchangersController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\LogsController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ServiceExchangerController;
use App\Http\Controllers\Api\ServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => '/auth'], function () {
    Route::post("/login", [AuthController::class, 'login']);
    Route::post("/register", [AuthController::class, 'register']);
});

Route::group(['prefix' => '/me', 'middleware' => ['auth:api']], function () {
    Route::get("/", [MeController::class, 'index']);
});


Route::group(['prefix' => '/account', 'middleware' => ['auth:api']], function () {
    Route::get("/", [AccountController::class, 'index']);
    Route::get("/{invoiceId}", [AccountController::class, 'show']);
    Route::post("/payment", [AccountController::class, 'payment']);
});

Route::group(['prefix' => '/services', 'middleware' => ['auth:api']], function () {
    Route::get("/", [ServicesController::class, 'index']);
    Route::post("/", [ServicesController::class, 'store']);
    Route::put("/{service}", [ServicesController::class, 'update']);
});

Route::group(['prefix' => '/service-exchangers', 'middleware' => ['auth:api']], function () {
    Route::get("/", [ServiceExchangerController::class, 'index']);
    Route::post("/", [ServiceExchangerController::class, 'store']);
    Route::put("/{serviceExchanger}", [ServiceExchangerController::class, 'update']);
});

Route::group(['prefix' => '/exchangers', 'middleware' => ['auth:api']], function () {
    Route::get("/by-service", [ExchangersController::class, 'getExchangersForService'])
        ->middleware('service-access:owner_service,admin_service,operator_service');
    Route::get("/", [ExchangersController::class, 'index'])->middleware(['is-admin-user']);
    Route::post("/", [ExchangersController::class, 'store'])->middleware(['is-admin-user']);
    Route::put("/{exchanger}", [ExchangersController::class, 'update'])->middleware(['is-admin-user']);
});

Route::group(['prefix' => '/invoices'], function () {
    Route::get("/", [InvoiceController::class, 'index'])->middleware('auth:api');
    Route::post("/", [InvoiceController::class, 'store'])->middleware('service-subscribe');
    Route::patch("/{invoice}/cancel", [InvoiceController::class, 'cancelByService'])->middleware('service-subscribe');
    Route::put("/{invoice}/cancel", [InvoiceController::class, 'cancelByOperator'])->middleware('auth:api', 'access-invoice-user');
    Route::put("/{invoice}/verified", [InvoiceController::class, 'acceptByOperator'])->middleware('auth:api', 'access-invoice-user');
    Route::put("/{invoice}", [InvoiceController::class, 'update'])->middleware('auth:api', 'access-invoice-user');
    Route::get("/check", [InvoiceController::class, 'check'])->middleware('service-subscribe');
});

Route::group(['prefix' => '/settings', 'middleware' => ['auth:api']], function () {
    Route::get("/menu", [MenuController::class, 'index']);
    Route::get("/roles", [EmployeeController::class, 'rolesForEmployee'])->middleware('admin-or-subscribe-or-role-in-service');
    Route::get("/qr", [InvoiceController::class, 'qrCode']);
    Route::get("/statuses", [InvoiceController::class, 'statuses']);
});

Route::group(['prefix' => '/employees', 'middleware' => ['auth:api']], function () {
    Route::get("/", [EmployeeController::class, 'index']);
    Route::post("/", [EmployeeController::class, 'store']);
    Route::put("/{employee}", [EmployeeController::class, 'update']);
    Route::delete("/{employee}", [EmployeeController::class, 'destroy']);
});

Route::group(['prefix' => "/logs", 'middleware' => ['auth:api', 'is-admin-user']], function () {
    Route::get("/php", [LogsController::class, 'php']);
    Route::get("/api", [LogsController::class, 'api']);
    Route::get("/invoices", [LogsController::class, 'invoices']);
});

Route::group(['prefix' => "/callback"], function () {
    Route::get("/racks", [CallbackController::class, 'racks']);
    Route::get("/bitloga", [CallbackController::class, 'bitloga']);
    Route::get("/luckypay", [CallbackController::class, 'luckypay']);
});
