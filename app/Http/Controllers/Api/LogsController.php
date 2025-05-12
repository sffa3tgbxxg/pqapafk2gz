<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logs\ApiErrorsRequest;
use App\Http\Requests\Logs\PhpErrorsRequest;
use App\Http\Resources\ApiErrorsResource;
use App\Http\Resources\PhpErrorsResource;
use App\Queries\ErrorsApiRequestsQuery;
use App\Queries\ErrorsPhpQuery;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function php(PhpErrorsRequest $request, ErrorsPhpQuery $errorsPhpQuery)
    {
        $result = $errorsPhpQuery->setFilters($request->filters())->generate();
        return PhpErrorsResource::collection($result);
    }

    public function api(ApiErrorsRequest $request, ErrorsApiRequestsQuery $errorsApiQuery)
    {
        $result = $errorsApiQuery->setFilters($request->filters())->generate();

        return ApiErrorsResource::collection($result);
    }

    public function invoices(Request $request)
    {

    }
}
