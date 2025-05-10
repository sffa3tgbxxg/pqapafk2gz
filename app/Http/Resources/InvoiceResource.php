<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'service' => ['name' => $this->service?->name],
            'payment_method' => ['name' => $this->exchanger?->name],
            'status' => ['code' => $this->statusOrig(), 'name' => $this->status],
            'comment' => $this->comment,
            'requisites' => $this->requisites,
            'user' => [
                'id' => $this->user?->external_id,
                'nickname' => $this->user?->nickname,
            ],
            'amount' => [
                'in_format' => number_format($this->amount_in, 2),
                'out_format' => number_format($this->amount_out, 2),
                'in' => $this->amount_in,
                'out' => $this->amount_out,
            ],
            'currency' => ['name' => $this->currency?->name, 'code' => $this->currency?->code],
            'details' => $this->details,
            'time' => [
                'created_at' => $this->created_at,
                'expiry_at' => $this->expiry_at,
                'created_at_format' => Carbon::parse($this->created_at)->format('d.m.Y H:i'),
                'expiry_at_format' => Carbon::parse($this->expiry_at)->format('d.m.Y H:i'),
            ],
        ];
    }
}
