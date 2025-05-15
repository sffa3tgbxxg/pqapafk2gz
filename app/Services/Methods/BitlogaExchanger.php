<?php

namespace App\Services\Methods;

use App\Exceptions\ServerErrorException;
use App\Models\Invoice;
use App\Repositories\clickhouse\InvoiceHistoryRepository;
use App\Services\Logger;
use App\Traits\Curl;
use Illuminate\Support\Facades\DB;


class BitlogaExchanger implements PaymentMethodContract
{
    use Curl;

    private string $endpoint;

    public function __construct(private InvoiceHistoryRepository $invoiceHistoryRepository)
    {
    }

    public function cancel(Invoice $invoice, string $status): void
    {
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
        $invoice = Invoice::query()
            ->lockForUpdate()
            ->where('id', $data['uniqueid'])
            ->first();

        if ($invoice == null) {
            Logger::error("[Bitloga] В отправленном Callback счет не найден", [
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
            Logger::error("[Bitloga] Не удалось обновить статус у заявки через Callback",
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
            default:
                Logger::error("Luckypay отправили неизвестный статус", [
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
        $response = $this->curlGet("{$endpoint}/api/v1/finance/balance", headers: ['Content-Type' => 'application/json', 'X-API-KEY' => $apiKey]);
        return (float)$response['deposit']['available'] ?? 0.0;
    }
}
