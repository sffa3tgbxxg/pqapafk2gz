<?php

namespace App\Http\Requests\_Logs;

use App\DTO\PhpErrors;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PhpErrorsRequest extends FormRequest
{
    private const DATE_FORMAT = 'd.m.Y H:i:s';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => ['required', 'date', 'date_format:' . self::DATE_FORMAT],
            'to' => ['required', 'date', 'date_format:' . self::DATE_FORMAT],
        ];
    }

    public function messages(): array
    {
        return [
            'from.required' => "Выберите дату от",
            'from.date' => "Выберите дату от",
            'from.date_format' => "Дата от должна быть в формате " . self::DATE_FORMAT,
            'to.required' => "Выберите дату до",
            'to.date' => "Выберите дату до",
            'to.date_format' => "Дата до должна быть в формате " . self::DATE_FORMAT,
        ];
    }

    public function filters(): PhpErrors
    {
        $dto = new PhpErrors();
        $dto->dateFrom = Carbon::parse($this->input('from'))->format('Y-m-d H:i:s');
        $dto->dateTo = Carbon::parse($this->input('to'))->format('Y-m-d H:i:s');
        $dto->page = $this->integer('page', 1);

        return $dto;
    }
}
