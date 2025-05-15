<?php

namespace App\Repositories\clickhouse;

use App\Services\Clickhouse\ClickhouseClient;

class InvoiceHistoryRepository extends ClickhouseClient
{
    public function insert(int $invoiceId, string $status, string $updatedBy, ?string $details = null, ?int $userId = null): void
    {
        $this->client->insert(
            'invoice_history',
            [
                [
                    $invoiceId,
                    $status,
                    $updatedBy,
                    $userId,
                    $details,
                    date('Y-m-d H:i:s'),
                ],
            ],
            [
                'invoice_id',
                'status',
                'updated_by',
                'user_id',
                'details',
                'time',
            ]
        );

    }
}
