<?php

namespace App\Services\Methods;

use App\Models\Exchanger;
use App\Models\Invoice;
use App\Repositories\clickhouse\InvoiceHistoryRepository;
use App\Services\Logger;
use App\Traits\Curl;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;


class GreengoExchanger implements PaymentMethodContract
{
    use Curl;

    private string $endpoint;

    public function __construct(private InvoiceHistoryRepository $invoiceHistoryRepository)
    {
    }

    public function cancel(Invoice $invoice, string $status): void
    {
        $this->curlPost("{$invoice->exchanger->endpoint}/api/v2/order/cancel", ['order_id' => [$invoice->external_id]],
            [
                'Api-Secret' => $invoice->serviceExchanger->api_key,
                'Content-Type' => 'application/json',
            ]
        );

        DB::beginTransaction();
        try {
            Invoice::query()->where('id', $invoice->id)->update(['status' => $status]);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }

        $this->invoiceHistoryRepository->insert($invoice->id, $status, "api", userId: auth()?->user()?->id ?? null);
    }

    public function getBalance(string $endpoint, string $apiKey, ?string $secretKey): float
    {
        return 0.0;
    }
}
