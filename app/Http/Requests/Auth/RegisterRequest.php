<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'unique:users,login'],
            'password' => ['required', 'string', 'min:6', 'max:42', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Введите логин',
            'login.string' => 'Введите логин',
            'login.unique' => 'Такой логин уже существует',
            'password.required' => 'Введите пароль',
            'password.string' => 'Введите пароль',
            'password.min' => 'Пароль должен быть от :min символов',
            'password.max' => 'Пароль должен быть до :max символов',
            'password.confirmed' => 'Пароли не совпадают',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge(
            ['login' => mb_strtolower($this->input('login'))],
        );
    }
}
