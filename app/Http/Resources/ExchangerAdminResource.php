<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangerAdminResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fee' => $this->fee,
            'auto_withdraw' => (bool) $this->auto_withdraw,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'min_withdraw' => $this->min_withdraw,
            'endpoint' => $this->endpoint,
            'active' => (bool) $this->active,
            'status' => $this->active ? "Включено" : "Отключено",
        ];
    }
}
