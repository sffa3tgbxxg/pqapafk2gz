<?php

namespace App\Http\Requests\Invoice;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SecondUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => ['nullable', 'string', 'max:3000'],
            'status' => ['required', 'string', Rule::in(array_keys(Invoice::STATUSES))],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.string' => "Заполните комментарий",
            'comment.max' => "Комментарий превышает :max символов",
            'status.required' => "Выберите статус",
            'status.string' => "Выберите статус",
        ];
    }
}
