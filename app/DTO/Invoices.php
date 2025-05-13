<?php

namespace App\DTO;

use Carbon\Carbon;

class Invoices
{
    public Carbon $dateFrom;
    public Carbon $dateTo;
    public string $user;
    public string $status;
    public int $serviceId;
    public int $exchangerId;
    public int $invoiceId;
    public int $externalInvoiceId;
    public bool $problem;
}
