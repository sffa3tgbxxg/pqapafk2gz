<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Http\Resources\PagesCollection;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(private readonly AccountService $accountService)
    {

    }

    public function payment(Request $request)
    {
        $invoice = $this->accountService->generate($request->attributes->get('auth_user')->id);
        return new AccountResource($invoice);
    }

    public function show(Request $request, int $invoiceId)
    {
        $invoice = $this->accountService->get($invoiceId, $request->attributes->get('auth_user')->id);
        return new AccountResource($invoice);
    }

    public function index(Request $request)
    {
        $invoices = $this->accountService->list($request->attributes->get('auth_user')->id);
        return AccountResource::collection($invoices);
    }
}
