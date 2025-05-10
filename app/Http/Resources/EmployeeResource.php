<?php

namespace App\Http\Resources;

use App\Services\EmployeeService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'login' => $this->user->login,
            'comment' => $this->comment,
            'contacts' => $this->contacts,
            'service_id' => $this->service->id,
            'service_name' => $this->service->name,
            'role' => $this->role->name,
            'role_code' => $this->role->nameOrig(),
            'created_at' => Carbon::parse($this->created_at)->isoFormat('D MMMM Y'),
            'accesses' => [
                'can_edit_delete' => app(EmployeeService::class)->canEditOrDelete($this->id)
            ]
        ];
    }
}
