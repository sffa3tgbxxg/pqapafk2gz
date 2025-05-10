<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255', 'exists:users,login'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'role' => ['required', 'string', 'in:admin_service,operator_service'],
            'contacts' => ['nullable', 'string', 'max:3000'],
            'comment' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'Выберите сервис',
            'service_id.integer' => 'Выберите сервис',
            'service_id.exists' => 'Сервис не найден',
            'login.required' => 'Пользователь не найден',
            'login.exists' => 'Пользователь не найден',
            'login.string' => 'Пользователь не найден',
            'login.max' => 'Поиск по логину превышает :max символов',
            'role.required' => 'Выберите должность',
            'role.in' => 'Выберите должность',
            'role.string' => 'Выберите должность',
            'contacts.max' => 'Контакты превышают длину в :max символов',
            'comment.max' => 'Комментарий превышает длину в :max символов',
        ];
    }
}
