<?php

namespace App\Http\Requests\Invoice;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'invoice_id' => ['nullable'],
            'user' => ['nullable', 'array'],
            'user.id' => ['nullable', 'integer'],
            'user.nickname' => ['nullable', 'string', 'max:255'],
            'user.balance' => ['nullable', 'numeric'],
        ];
    }
}
