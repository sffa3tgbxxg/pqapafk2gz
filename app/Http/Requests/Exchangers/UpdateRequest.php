<?php

namespace App\Http\Requests\Exchangers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->attributes->get("auth_user")->isAdmin();
    }

    public function rules(): array
    {
        return [
            "name" => ["nullable", "string", "max:255"],
            "fee" => ["nullable", "numeric", "min:1"],
            "auto_withdraw" => ["nullable", "boolean"],
            "min_amount" => ["nullable", "integer", "min:1500"],
            "max_amount" => ["nullable", "integer", "min:5000"],
            "min_withdraw" => ["nullable", "integer"],
            "endpoint" => ["nullable", "string", "max:255"],
            "active" => ["nullable", "boolean"],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => "Введите название",
            "name.string" => "Введите название",
            "name.max" => "Название превышает :max символов",
            "fee.required" => "Введите комиссию",
            "fee.numeric" => "Введите комиссию",
            "fee.min" => "Комиссия должна быть больше :min %",
            "auto_withdraw.required" => "Выберите состояние для авто-вывода",
            "auto_withdraw.boolean" => "Выберите состояние для авто-вывода",
            "max_amount.required" => "Введите максимальную сумму для инвойса",
            "max_amount.integer" => "Введите максимальную сумму для инвойса",
            "max_amount.min" => "Максимальная сумма должна быть больше :min рублей",
            "min_amount.required" => "Введите минимальную сумму для инвойса",
            "min_amount.integer" => "Введите минимальную сумму для инвойса",
            "min_amount.min" => "Минимальная сумма должна быть больше :min рублей",
            "endpoint.required" => "Введите Endpoint API",
            "active.required" => "Выберите состояние",
            "active.boolean" => "Выберите состояние"
        ];
    }
}
