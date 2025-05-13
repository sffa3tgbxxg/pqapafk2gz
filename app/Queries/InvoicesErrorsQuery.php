<?php

namespace App\Queries;

use App\DTO\InvoicesErrors;
use App\Services\Clickhouse\ClickhouseClient;

class InvoicesErrorsQuery extends ClickhouseClient
{
    private InvoicesErrors $filters;

    public function generate(): array
    {
        return [
            'data' => $this->query(),
            'count' => $this->query(false),
        ];
    }

    public function query($limit = true): array|int
    {
        $condition = $limit ? $this->condition() : $this->condition(false);

        $query = "
           SELECT 
                invoice_id,
                error_message as message,
                time as created_at
            FROM invoices_errors_logs
                {$condition}
        ";

        if ($limit) {
            $offset = ($this->filters->page - 1) * $this->filters->limit;
            $query .= " ORDER BY time DESC";
            $query .= " LIMIT {$offset}, {$this->filters->limit} ";
        }


        return $limit ? $this->client->select($query)->rows() : $this->client->select($query)->count();
    }

    private function condition($limit = true): string
    {
        $condition = " WHERE";
        $condition .= " time BETWEEN '{$this->filters->dateFrom}' AND '{$this->filters->dateTo}'";

        if ($this->filters->invoiceId) {
            $condition .= " AND invoice_id = {$this->filters->invoiceId}";
        }

        return $condition;
    }

    public function setFilters(InvoicesErrors $filters): self
    {
        $this->filters = $filters;

        return $this;
    }
}
