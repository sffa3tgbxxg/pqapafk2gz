<?php

namespace App\Http\Requests\Invoice;

use App\DTO\Invoices;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    private const DATE_FORMAT = "d.m.Y";

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date', 'date_format:' . self::DATE_FORMAT],
            'to' => ['required', 'date', 'after_or_equal:from', 'date_format:' . self::DATE_FORMAT],
            'user' => ['nullable', 'string'],
            'service_id' => ['nullable', 'integer'],
            'exchanger_id' => ['nullable', 'integer'],
            'id' => ['nullable', 'integer'],
            'external_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', Rule::in(array_keys(Invoice::STATUSES))],
            'problem' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'from.required' => 'Заполните дату от',
            'from.date' => 'Заполните дату от',
            'from.date_format' => 'Дата от должна быть в формате ' . self::DATE_FORMAT,


            'to.date_format' => 'Дата до должна быть в формате ' . self::DATE_FORMAT,
            'to.required' => 'Заполните дату до',
            'to.date' => 'Заполните дату до',
            'to.after_or_equal' => 'Дата до должна быть меньше или равна дате от',

        ];
    }

    public function getFilters(): Invoices
    {
        $invoiceDTO = new Invoices();
        $invoiceDTO->dateFrom = Carbon::parse($this->date('from', self::DATE_FORMAT))->startOfDay();
        $invoiceDTO->dateTo = Carbon::parse($this->date('to', self::DATE_FORMAT))->endOfDay();
        $invoiceDTO->user = $this->string('user');
        $invoiceDTO->exchangerId = $this->integer('exchanger_id');
        $invoiceDTO->serviceId = $this->integer('service_id');
        $invoiceDTO->invoiceId = $this->integer('id');
        $invoiceDTO->externalInvoiceId = $this->integer('external_id');
        $invoiceDTO->status = $this->string('status');
        $invoiceDTO->problem = $this->boolean('problem');

        return $invoiceDTO;
    }
}
