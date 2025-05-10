<?php

namespace App\Facades;

use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\InvoiceService generate(\App\Models\Service|int $service, array $data)
 * @method static \App\Services\InvoiceService setById(int $invoiceId)
 * @method static Collection getByIds(array $ids, int $serviceId = 0)
 */
class InvoiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'invoicefacade';
    }
}
