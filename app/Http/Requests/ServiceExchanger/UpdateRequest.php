<?php

namespace App\Http\Requests\ServiceExchanger;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'active' => ['boolean'],
            'api_key' => ['nullable', 'string', 'max:600'],
            'secret_key' => ['nullable', 'string', 'max:600'],
            'fee' => ['nullable', 'numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'active.boolean' => 'Выберите состояние',
            'api_key.string' => 'Введите API ключ',
            'api_key.max' => 'API Ключ превышает максимально допустимую длину',
            'secret_key.string' => 'Введите Secret ключ',
            'secret_key.max' => 'Secret Ключ превышает максимально допустимую длину',
            'fee.numeric' => "Заполните поле комиссия",
            'fee.min' => 'Комиссия должна быть больше или равна :min %'
        ];
    }
}
