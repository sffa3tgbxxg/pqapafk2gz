<?php

namespace App\Http\Requests\Employee;

use App\Services\EmployeeService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(EmployeeService::class)->canEditOrDelete($this->route('employee'));
    }

    public function rules(): array
    {
        return [
            'role' => ['nullable', 'string', 'in:admin_service,operator_service'],
            'contacts' => ['nullable', 'string', 'max:3000'],
            'comment' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Выберите должность',
            'role.string' => 'Выберите должность',
            'contacts.max' => 'Контакты превышают длину в :max символов',
            'comment.max' => 'Комментарий превышает длину в :max символов',
        ];
    }
}
