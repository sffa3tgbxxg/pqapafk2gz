<?php

namespace App\Services\Methods;

use App\Exceptions\ServerErrorException;
use App\Models\Invoice;
use App\Repositories\clickhouse\InvoiceHistoryRepository;
use App\Services\Logger;
use App\Traits\Curl;
use Illuminate\Support\Facades\DB;


class RacksExchanger implements PaymentMethodContract
{
    use Curl;

    private string $endpoint;

    public function __construct(private InvoiceHistoryRepository $invoiceHistoryRepository)
    {
    }

    public function cancel(Invoice $invoice, string $status): void
    {
        $this->curlGet("{$invoice->exchanger->endpoint}/fiat_api/cancel", ['id' => [$invoice->external_id]],
            [
                'Authorization' => "Bearer {$invoice->serviceExchanger->api_key}",
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

    public function handleCallback(array $data): void
    {
        $order = $data['order'][0] ?? null;
        if ($order == null) {
            Logger::error("[Racks] В отправленном Callback счет не найден", [
                'Callback' => json_encode($data),
            ]);
            throw new ServerErrorException();
        }

        $invoice = Invoice::query()
            ->lockForUpdate()
            ->where('external_id', $order['id'])
            ->first();

        if ($invoice == null) {
            Logger::error("[Racks] В отправленном Callback счет не найден", [
                'Callback' => json_encode($data),
            ]);
            throw new ServerErrorException();
        }

        DB::beginTransaction();
        try {
            $status = $this->processStatus($invoice, $data['status'] ?? "");
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error("[Racks] Не удалось обновить статус у заявки через Callback R",
                [
                    'message' => $exception->getMessage()
                ]);
            throw new ServerErrorException();
        }

        $this->invoiceHistoryRepository->insert($invoice->id, $status, "callback", json_encode($data));
    }

    private function processStatus(Invoice $invoice, string $status): string
    {
        switch ($status) {
            case 'Payed':
                $status = Invoice::PAID;
                break;
            case 'Canceled':
                $status = Invoice::CANCEL_TIME;
                break;
            case 'Error':
                $status = Invoice::ERROR;
                break;
            case "Pending":
                $status = Invoice::PENDING;
                break;
            default:
                Logger::error("[Racks] отправили неизвестный статус", [
                    'status' => $status,
                    'invoice' => json_encode($invoice),
                ]);
                throw new ServerErrorException();
        }

        $invoice->update(['status' => $status]);

        return $status;
    }

    public function getBalance(string $endpoint, string $apiKey, ?string $secretKey): float
    {
        $response = $this->curlGet("{$endpoint}/fiat_api/balance", ['private_key' => $secretKey], headers: ['Content-Type' => 'application/json', 'Authorization' => "Bearer {$apiKey}"]);
        return (float)$response['deposit']['available'] ?? 0.0;
    }
}
