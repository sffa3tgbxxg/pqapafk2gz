<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Account;
use App\Models\Currency;
use App\Models\PriceSubscription;
use App\Models\Subscriber;
use App\Services\Currency\CurrencyService;
use App\Traits\Curl;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AccountService
{
    use Curl;

    public function __construct(private CurrencyService $currencyService, private CryptoAddressService $cryptoAddressService)
    {
    }

    public function generate(int $userId): Account
    {
        [$amount, $exRate] = $this->currencyService->convertAmount(Currency::BTC, PriceSubscription::getPrice());

        DB::beginTransaction();
        try {
            $invoice = Account::query()
                ->create([
                    'user_id' => $userId,
                    'amount' => $amount,
                    'ex_rate' => $exRate,
                    'currency_id' => Currency::getIdByCode(Currency::BTC),
                    'address_id' => $this->cryptoAddressService->generate(Currency::BTC)?->id,
                    'status' => Account::PENDING,
                    'expiry_at' => now()->addMinutes(180),
                ]);
            DB::commit();
        } catch (Throwable $exception) {
            Logger::error("Не удалось создать счет на оплату подписки. Error: " . json_encode(
                    [
                        'message' => $exception->getMessage(),
                    ]
                ));
            DB::rollBack();
            throw new ServerErrorException();
        }

        return $invoice;
    }

    public function get(int $invoiceId, int $userId = null): Account
    {
        try {
            return Account::query()
                ->with('currency')
                ->where('id', $invoiceId)
                ->where('expiry_at', '>=', now())
                ->where('status', Account::PENDING)
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })->firstOrFail();
        } catch (\Throwable $exception) {
            Logger::error("Не удалось получить счет на оплату подписки", [
                'message' => $exception->getMessage(),
            ]);
            throw new ServerErrorException();
        }
    }

    public function list(int $userId): Collection
    {
        return Account::query()
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();
    }


    public function canceledExpired(): void
    {
        Account::query()
            ->where('status', Account::PENDING)
            ->where('expiry_at', '<', now())
            ->update(['status' => Account::CANCELED]);
    }

    public function initPaymentCheck(): bool
    {
        $invoices = $this->getInvoicesByStatus(Account::PENDING);

        if (empty($invoices)) {
            return true;
        }

        $requisites = implode("|", (clone $invoices)->map(function ($invoice) {
            return $invoice->requisites();
        })->toArray());

        $result = $this->curlGet(config('keys.blockchain_api_url') . "/balance?active", ['active' => $requisites], ['Content-Type' => 'application/json']);
        Log::info("result:" . json_encode($result));

        if (empty($result)) {
            throw new \Exception("Не удалось получить биткоин адреса");
        }


        foreach ($result as $key => $value) {
            $invoice = $this->getByRequisites($key);
            Log::info("invoice: " . json_encode($invoice));
            if ($invoice == null) {
                continue;
            }


            if ($invoice->amount <= ((float)$value['total_received']) / 100000000) {
                Log::info("test");
                DB::beginTransaction();
                try {
                    $this->cryptoAddressService->updateBalance($invoice->address_id, ((float)$value['total_received'] / 100000000));
                    $this->updateStatus($invoice->id, Account::PAID);
                    $this->setSubscribe($invoice->user_id);
                    DB::commit();
                } catch (Throwable $exception) {
                    DB::rollBack();
                    Logger::error("Не удалось выдать подписку UserID: {$invoice->user_id} " . json_encode(
                            [
                                'message' => $exception->getMessage(),
                                'trace' => $exception->getTraceAsString(),
                            ]
                        ));
                    continue;
                }
            }
        }

        return true;
    }

    public function updateStatus(int $invoiceId, string $status): void
    {
        Account::query()
            ->where('id', $invoiceId)
            ->update(['status' => $status]);
    }

    public function getByRequisites(string $requisites): Account
    {
        return Account::query()
            ->whereHas('address', function (Builder $query) use ($requisites) {
                $query->where('address', $requisites);
            })
            ->first();
    }

    public function getInvoicesByStatus(string $status): Collection
    {
        return Account::query()
            ->with('address')
            ->where('status', $status)
            ->get();
    }

    public function setSubscribe(int $userId)
    {
        $expiryAt = now()->addMonths(1);
        $subscriber = Subscriber::query()
            ->where('user_id', $userId)
            ->first();


        if ($subscriber != null) {
            if ($subscriber->expiry_at > now()) {
                $expiryAt = Carbon::parse($subscriber->expiry_at)->addMonths(1);
            }
        }

        Subscriber::query()->updateOrCreate(['user_id' => $userId], ['expiry_at' => $expiryAt]);
    }
}
