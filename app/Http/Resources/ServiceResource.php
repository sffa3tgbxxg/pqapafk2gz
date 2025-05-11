<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'api_key' => $this->api_key,
            'role' => $this->role($request->attributes->get('auth_user')?->id)?->name,
            'active' => (bool)$this->active,
            'status' => $this->active ? 'Работает' : 'Отключено',
        ];
    }

}
