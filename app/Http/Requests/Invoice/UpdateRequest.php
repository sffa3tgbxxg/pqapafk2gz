<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => "Заполните комментарий",
            'comment.string' => "Заполните комментарий",
            'comment.max' => "Комментарий превышает :max символов",
        ];
    }
}
