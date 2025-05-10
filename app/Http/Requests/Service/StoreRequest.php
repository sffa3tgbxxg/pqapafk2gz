<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->attributes->get('auth_user')->isSubscribe();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:52'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Введите название",
            'name.string' => "Введите название",
            'name.max' => "Название превышает :max символов",
        ];
    }
}
