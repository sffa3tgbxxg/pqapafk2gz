<?php

namespace App\DTO;

class ApiRequestsErrors
{
    public string $dateFrom;
    public string $dateTo;
    public ?int $invoiceId = null;
    public ?int $exchangerId = null;
}
