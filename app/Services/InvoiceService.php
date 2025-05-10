<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Facades\RabbitMQ;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceExchanger;
use App\Models\UserService;
use App\Services\Methods\PaymentMethodContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    private Invoice $invoice;
    private Service $service;

    /**
     * @throws ServerErrorException
     */
    public function generate(Service|int $service, array $data): Invoice
    {
        if (!($service instanceof Service)) {
            $service = Service::query()->find($service);
        }

        $invoice = $this->createInvoiceBase($service->id, $data);
        RabbitMQ::publish('invoices_exchange', 'invoice.create', 'invoices', $this->constructRabbitMQData($service, $invoice));
        return $invoice;
    }

    public function getByIds(array $ids, int $serviceId = 0): Collection
    {
        return Invoice::query()
            ->whereIn('id', $ids)
            ->when($serviceId !== 0, function ($query) use ($serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->get();
    }

    public function setById(int $invoiceId): self
    {
        $this->invoice = Invoice::query()->findOrFail($invoiceId);
        return $this;
    }


    public function setService(Service $service): self
    {
        $this->service = $service;
        return $this;
    }

    public function cancelInvoice(bool $isServiceInvoice = true): self
    {
        if ($isServiceInvoice) {
            if ($this->invoice->service_id != $this->service->id) {
                Logger::error("Попытка отменить инвойс InvoiceID: {$this->invoice->id} от лица другого сервиса");
                throw new ServerErrorException();
            }
        }
        $status = $isServiceInvoice ? Invoice::CANCEL : Invoice::CANCEL_OPERATOR;

        $class = "App\\Services\\Methods\\{$this->invoice->name}Excahanger";
        if (class_exists($class) && in_array(PaymentMethodContract::class, class_implements($class))) {
            $method = new $class();
            $method->cancel($this->invoice, $status);
        } else {
            DB::beginTransaction();
            try {
                $this->updateStatus($status);
                DB::commit();
            } catch (\Throwable $exception) {
                DB::rollBack();
                Logger::error($exception->getMessage());
                throw new ServerErrorException();
            }
        }

        return $this;
    }

    public function getInvoice(): Invoice
    {
        return $this->invoice->refresh();
    }

    public function updateStatus(string $status): self
    {
        Invoice::query()->where('id', $this->invoice->id)->update(['status' => $status]);

        return $this;
    }

    public function setComment(string $comment): self
    {
        Invoice::query()->where('id', $this->invoice->id)->update(['comment' => $comment]);

        return $this;
    }

    /**
     * @throws ServerErrorException
     */
    private function createInvoiceBase(int $serviceId, array $data): Invoice
    {
        DB::beginTransaction();
        try {
            if (!empty($data['user'])) {
                $user = UserService::query()
                    ->where('service_id', $serviceId)
                    ->when(!empty($data['user']['id']), fn(Builder $query) => $query->where('external_id', $data['user']['id']))
                    ->when(!empty($data['user']['nickname']), fn(Builder $query) => $query->where('nickname', $data['user']['nickname']))
                    ->first();

                if ($user === null && !empty($data['user']['id'])) {
                    $user = UserService::query()
                        ->firstOrCreate(
                            [
                                'service_id' => $serviceId,
                                'external_id' => $data['user']['id'],
                            ],
                            [
                                'nickname' => $data['user']['nickname'] ?? null,
                                'balance' => $data['user']['balance'] ?? 0.00,
                            ]
                        );
                }
            }

            $invoice = Invoice::query()->create([
                'status' => Invoice::SEARCH,
                'service_id' => $serviceId,
                'amount_out' => $data['amount'],
                'amount_in' => 0,
                'user_id' => $user?->id ?? null,
                'currency_id' => Currency::getIdByCode(Currency::RUBLES)
            ]);

            DB::commit();
            return $invoice;
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error("Cant create base invoice: " . $exception->getMessage());
            throw new ServerErrorException();
        }
    }

    private function constructRabbitMQData(Service $service, Invoice $invoice): array
    {
        $data = [
            'invoice' => [
                'id' => $invoice->id,
                'created_at' => $invoice->created_at,
            ],
            'exchangers' => []];
        $exchangers = $service->exchangers()
            ->where('active', true)
            ->whereHas('exchanger', function (Builder $query) use ($invoice) {
                $query->where('min_amount', '<=', $invoice->amount_out);
                $query->where('max_amount', '>=', $invoice->amount_out);
            })
            ->with('exchanger')
            ->get();

        foreach ($exchangers as $serviceExchanger) {
            $data['exchangers'][] = [
                'id' => $serviceExchanger->exchanger->id,
                'endpoint' => $serviceExchanger->exchanger->endpoint,
                'name' => $serviceExchanger->exchanger->name,
                'amount' => $this->constructAmountWithFee($serviceExchanger, $invoice->amount_out),
                'api_key' => $serviceExchanger->api_key,
                'secret_key' => $serviceExchanger->secret_key,
            ];
        }

        return $data;
    }

    private function constructAmountWithFee(ServiceExchanger $serviceExchanger, float $amount): float
    {
        $feeService = $serviceExchanger->fee;
        $feeExchanger = $serviceExchanger?->exchanger->fee;
        return ((($feeService + $feeExchanger) / 100) * $amount) + $amount;
    }
}
