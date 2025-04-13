<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['name', 'active'];
    public $timestamps = true;

    public function role(int $userId): ?string
    {
        return $this->employees()->where('user_id', $userId)->first()?->role?->name ?? null;
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'service_id', 'id');
    }

    public function exchangers(): HasMany
    {
        return $this->hasMany(ServiceExchanger::class, 'service_id', 'id');
    }
}
