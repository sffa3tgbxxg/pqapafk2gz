<?php

namespace App\Http\Requests\Exchangers;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->attributes->get("auth_user")->isAdmin();
    }

    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "fee" => ["required", "numeric", "min:1"],
            "auto_withdraw" => ["required", "boolean"],
            "min_amount" => ["required", "integer", "min:1500"],
            "max_amount" => ["required", "integer", "min:5000"],
            "min_withdraw" => ["required", "integer"],
            "endpoint" => ["required", "string", "max:255"],
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
        ];
    }
}
