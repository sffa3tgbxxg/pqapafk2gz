<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceExchangerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service_name' => $this->service->name,
            'exchanger_name' => $this->exchanger->name,
            'api_key' => $this->api_key,
            'secret_key' => $this->secret_key,
            'turnover' => 0,
            'fee' => [
                'service' => $this->fee,
                'exchanger' => $this->exchanger->fee,
                'total' => ($this->fee + $this->exchanger->fee),
            ],
            'balance' => number_format($this->balance, 2),
            'active' => (bool)$this->active,
            'status' => $this->active ? 'Работает' : 'Отключено',
        ];
    }
}
