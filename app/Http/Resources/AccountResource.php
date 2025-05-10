<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'requisites' => $this->requisites(),
            'amount' => $this->formatValue($this->amount),
            'status' => [
                'name' => $this->status,
                'code' => $this->statusOrig(),
            ],
            'currency' => [
                'code' => $this->currency->code,
            ],
            'created_at' => Carbon::parse($this->created_at)->isoFormat('D MMMM HH:m'),
            'expiry_at' => $this->expiry_at,
        ];
    }

    private function formatValue(float $value): string
    {
        $formatted = number_format($value, 8, '.', '');
        return rtrim(rtrim($formatted, '0'), '.');
    }
}
