<?php

namespace App\Queries;

use App\DTO\PhpErrors;
use App\Services\Clickhouse\ClickhouseClient;

class ErrorsPhpQuery extends ClickhouseClient
{
    private PhpErrors $filters;

    public function generate(): array
    {
        return [
            'data' => $this->query(),
            'count' => $this->query(false),
        ];
    }

    public function query($limit = true): array|int
    {
        $query = "
        SELECT 
            error_message as message, 
            time as created_at
        FROM php_errors_logs
        WHERE 
            time BETWEEN :dateFrom AND :dateTo
        ";

        if ($limit) {
            $offset = ($this->filters->page - 1) * $this->filters->limit;
            $query .= " ORDER BY time DESC";
            $query .= " LIMIT {$offset}, {$this->filters->limit} ";
        }

        $args = [
            'dateFrom' => $this->filters->dateFrom,
            'dateTo' => $this->filters->dateTo,
        ];


        return $limit ? $this->client->select($query, $args)->rows() : $this->client->select($query, $args)->count();
    }

    public function setFilters(PhpErrors $filters): self
    {
        $this->filters = $filters;

        return $this;
    }
}
