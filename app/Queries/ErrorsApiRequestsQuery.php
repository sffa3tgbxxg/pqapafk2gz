<?php

namespace App\Queries;

use App\DTO\ApiRequestsErrors;
use App\Services\Clickhouse\ClickhouseClient;

class ErrorsApiRequestsQuery extends ClickhouseClient
{
    private ApiRequestsErrors $filters;

    public function generate()
    {
        $condition = $this->getCondition();

        $sql = "
        SELECT
            invoice_id,
            exchanger_id,
            dictGetString('exchangers_dictionary', 'name', toUInt64(exchanger_id)) AS exchanger_name,
            error_message as message,
            time as created_at
         FROM api_error_requests
            {$condition}
        ";

        return $this->client->select($sql)->rows();
    }

    public function setFilters(ApiRequestsErrors $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    private function getCondition(): string
    {
        $condition = " WHERE";

        $condition .= " time BETWEEN {$this->filters->dateFrom} AND {$this->filters->dateTo}";

        if ($this->filters->invoiceId) {
            $condition .= " AND invoice_id = {$this->filters->invoiceId}";
        }
        if ($this->filters->exchangerId) {
            $condition .= " AND exchanger_id = {$this->filters->exchangerId}";
        }

        return $condition;
    }
}
