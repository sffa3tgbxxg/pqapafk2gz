<?php

namespace App\Queries;

use App\DTO\PhpErrors;
use App\Services\Clickhouse\ClickhouseClient;

class ErrorsPhpQuery extends ClickhouseClient
{
    private PhpErrors $filters;

    public function generate(): array
    {
        $query = "
        SELECT 
            error_message as message, 
            time as created_at
        FROM php_errors_logs
        WHERE 
            time BETWEEN :dateFrom AND :dateTo
        ";

        $args = [
            'dateFrom' => $this->filters->dateFrom,
            'dateTo' => $this->filters->dateTo,
        ];


        return $this->client->select($query, $args)->rows();
    }

    public function setFilters(PhpErrors $filters): self
    {
        $this->filters = $filters;

        return $this;
    }
}
