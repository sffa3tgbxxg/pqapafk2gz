<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logs\ApiErrorsRequest;
use App\Http\Requests\Logs\InvoicesErrorsRequest;
use App\Http\Requests\Logs\PhpErrorsRequest;
use App\Http\Resources\ApiErrorsResource;
use App\Http\Resources\InvoicesErrorsResource;
use App\Http\Resources\PhpErrorsResource;
use App\Queries\ErrorsApiRequestsQuery;
use App\Queries\ErrorsPhpQuery;
use App\Queries\InvoicesErrorsQuery;

class LogsController extends Controller
{
    public function php(PhpErrorsRequest $request, ErrorsPhpQuery $errorsPhpQuery)
    {
        $result = $errorsPhpQuery->setFilters(($filters = $request->filters()))->generate();
        return PhpErrorsResource::collection($result['data'])->additional(['meta' => [
            'current_page' => $request->integer("page"),
            "last_page" => ceil($result['count'] > 0 ? $result['count'] / $filters->limit : 1),
        ]]);
    }

    public function api(ApiErrorsRequest $request, ErrorsApiRequestsQuery $errorsApiQuery)
    {
        $result = $errorsApiQuery->setFilters(
            ($filters = $request->filters())
        )->generate();

        return ApiErrorsResource::collection($result['data'])
            ->additional(['meta' => [
                'current_page' => $request->integer("page"),
                'last_page' => ceil($result['count'] > 0 ? $result['count'] / $filters->limit : 1),
            ]]);
    }

    public function invoices(InvoicesErrorsRequest $request, InvoicesErrorsQuery $errorsInvoicesQuery)
    {
        $result = $errorsInvoicesQuery->setFilters(
            ($filters = $request->filters())
        )->generate();

        return InvoicesErrorsResource::collection($result['data'])->additional(['meta' => [
            'current_page' => $request->integer("page"),
            'last_page' => ceil($result['count'] > 0 ? $result['count'] / $filters->limit : 1),
        ]]);
    }
}
