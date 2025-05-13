<?php

namespace App\DTO;

class InvoicesErrors
{
    public string $dateFrom;
    public string $dateTo;
    public ?int $invoiceId = null;
    public int $page = 1;
    public int $limit = 20;
}
