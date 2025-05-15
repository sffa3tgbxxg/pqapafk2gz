<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ServerErrorException;
use App\Facades\InvoiceFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CheckRequest;
use App\Http\Requests\Invoice\IndexRequest;
use App\Http\Requests\Invoice\SecondUpdateRequest;
use App\Http\Requests\Invoice\StoreRequest;
use App\Http\Requests\Invoice\UpdateRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Queries\InvoicesQuery;
use App\Services\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class InvoiceController extends Controller
{
    public function __construct()
    {
    }

    public function index(IndexRequest $request, InvoicesQuery $invoicesQuery)
    {
        $data = $invoicesQuery
            ->setFilters($request->getFilters())
            ->generate();

        return InvoiceResource::collection($data);
    }

    public function store(StoreRequest $request)
    {
        $invoice = InvoiceFacade::generate(
            $request->attributes->get('service'),
            $request->validated()
        );
        return new InvoiceResource($invoice);
    }

    public function acceptByOperator(UpdateRequest $request, int $invoiceId)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $invoice = InvoiceFacade::setById($invoiceId)
                ->updateStatus(Invoice::PAID_OPERATOR)
                ->setComment($data['comment'])
                ->getInvoice();
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error($exception);
            throw new ServerErrorException();
        }

        return new InvoiceResource($invoice);
    }

    /**
     * @throws ServerErrorException
     */
    public function cancelByService(Request $request, int $invoiceId): InvoiceResource
    {
        DB::beginTransaction();
        try {
            $invoice = InvoiceFacade::setById($invoiceId)
                ->setService($request->attributes->get('service'))
                ->cancelInvoice()
                ->getInvoice();
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error("Не удалось отменить счет вручную",
                [
                    'message' => $exception->getMessage(),
                ]);
            throw new ServerErrorException();
        }

        return new InvoiceResource($invoice);
    }

    /**
     * @throws ServerErrorException
     */
    public function cancelByOperator(UpdateRequest $request, int $invoiceId): InvoiceResource
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $invoice = InvoiceFacade::setById($invoiceId)
                ->cancelInvoice(false)
                ->setComment($data['comment'])
                ->getInvoice();

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error("Не удалось отменить счет вручную",
                [
                    'message' => $exception->getMessage(),
                ]);
            throw new ServerErrorException();
        }

        return new InvoiceResource($invoice);
    }

    public function qrCode(Request $request)
    {
        $data = $request->validate([
            'target' => ['required', 'string', 'max:500']
        ]);

        $svg = QrCode::format('svg')->size(200)->generate($data['target']);

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }

    public function check(CheckRequest $request)
    {
        $invoices = InvoiceFacade::getByIds($request->validated()['ids'], $request->attributes->get('service')?->id);

        return InvoiceResource::collection($invoices);
    }

    public function update(SecondUpdateRequest $request, int $invoiceId)
    {
        $data = $request->validated();

        $invoice = InvoiceFacade::setById($invoiceId);
        DB::beginTransaction();
        try {
            $invoice = $invoice
                ->setComment($data['comment'])
                ->updateStatus($data['status'])
                ->getInvoice();
            DB::commit();
            return new InvoiceResource($invoice);
        } catch (\Throwable $exception) {
            DB::rollBack();
            Logger::error("Не удалось отменить счет вручную",
                [
                    'message' => $exception->getMessage(),
                ]);
            throw new ServerErrorException();
        }
    }

    public function statuses()
    {
        $data = Invoice::getStatusesForOperator();

        return response()->json(
            array_map(function ($key, $val) {
                return ['code' => $key, 'name' => $val];
            }, array_keys($data), array_values($data))
            , 200);
    }
}
