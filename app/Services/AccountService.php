<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Account;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccountService
{
    public function generate(int $userId): Account
    {
        return DB::transaction(function () use ($userId) {
            return Account::query()
                ->create([
                    'user_id' => $userId,
                    'amount' => 0.0000005,
                    'amount_rub' => 10000,
                    'requisites' => 'bc1gdsfgdfshjkkkdfIFGJgn3gjsgjsdgn34jg',
                    'status' => Account::PENDING,
                    'expiry_at' => now()->addMinutes(180),
                ]);
        });
    }

    public function get(int $invoiceId, int $userId = null): Account
    {
        try {
            return Account::query()
                ->where('id', $invoiceId)
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })->firstOrFail();
        } catch (\Exception $exception) {
            throw new ServerErrorException();
        }
    }

    public function list(int $userId): LengthAwarePaginator
    {
        return Account::query()
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }


    public function canceledExpired(): void
    {
        Account::query()
            ->where('status', Account::PENDING)
            ->where('expiry_at', '<', now())
            ->update(['status' => Account::CANCELED]);
    }
}
