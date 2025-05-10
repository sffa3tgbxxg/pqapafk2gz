<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->attributes->get('auth_user')->isSubscribe();
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:52'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => "Введите название",
            'name.max' => "Название превышает :max символов",
            'active.boolean' => 'Выберите состояние'
        ];
    }
}
