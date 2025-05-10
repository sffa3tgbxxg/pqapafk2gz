<?php

namespace App\Services\Methods;

use App\Models\Exchanger;
use App\Models\Invoice;
use App\Services\Logger;
use App\Traits\Curl;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;


class GreengoExchanger implements PaymentMethodContract
{
    use Curl;

    private string $endpoint;

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
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error($exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }

    public function getBalance(string $endpoint, string $apiKey): float
    {
        return 0.0;
    }
}
