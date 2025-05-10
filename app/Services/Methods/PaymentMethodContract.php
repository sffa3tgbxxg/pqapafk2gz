<?php

namespace App\Services\Methods;

use App\Models\Invoice;

interface PaymentMethodContract
{
    public function cancel(Invoice $invoice, string $status): void;

    public function getBalance(string $endpoint, string $apiKey): float;
}
