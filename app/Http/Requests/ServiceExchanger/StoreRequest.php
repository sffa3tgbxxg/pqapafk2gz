<?php

namespace App\Http\Requests\ServiceExchanger;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'exchanger_id' => ['required', 'integer', 'exists:exchangers,id'],
            'api_key' => ['required', 'string', 'max:600'],
            'secret_key' => ['nullable', 'string', 'max:600'],
            'fee' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => "Выберите сервис",
            'service_id.integer' => "Выберите сервис",
            'service_id.exists' => "Сервис не найден",
            'exchanger_id.required' => "Выберите обменник",
            'exchanger_id.integer' => "Выберите обменник",
            'exchanger_id.exists' => "Обменник не найден",
            'api_key.required' => "Введите API-ключ",
            'api_key.string' => "Введите API-ключ",
            'api_key.max' => "API-ключ превышает :max символов",
            'secret_key.string' => "Введите Secret-ключ",
            'secret_key.max' => "Secret-ключ превышает :max символов",
            'fee.required' => "Заполните поле комиссия",
            'fee.numeric' => "Заполните поле комиссия",
            'fee.min' => 'Комиссия должна быть больше или равна :min %',

        ];
    }
}
