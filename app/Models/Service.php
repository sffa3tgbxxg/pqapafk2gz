<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['name', 'api_key', 'active'];
    public $timestamps = true;

    public function role(int $userId): ?Role
    {
        return $this->employees()
            ->where('user_id', $userId)
            ->with('role')
            ->first()?->role ?? null;
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'service_id', 'id');
    }

    public function exchangers(): HasMany
    {
        return $this->hasMany(ServiceExchanger::class, 'service_id', 'id')->whereHas('exchanger', function (Builder $query) {
            $query->where('active', true);
        });
    }

    public function hasSubscribe(): bool
    {
        return $this->employees()
            ->where('role_id', Role::getIdByName(Role::OWNER_SERVICE))
            ->first()?->user?->isSubscribe();
    }

    public function employee(int $userId)
    {
        return $this->employees()
            ->with('role')
            ->where('user_id', $userId)
            ->first();
    }
}
