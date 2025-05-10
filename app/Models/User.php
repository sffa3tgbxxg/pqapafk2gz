<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'login',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'login' => 'string',
            'password' => 'hashed',
        ];
    }

    public $timestamps = true;

    public static function boot()
    {
        parent::boot();

        static::updated(function ($user) {
            Cache::forget("user:{$user->id}");
        });
    }

    public function login(): Attribute
    {
        return Attribute::make(set: fn($val) => mb_strtolower($val));
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'user_id', 'id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscriber::class, 'user_id', 'id');
    }

    public function isSubscribe(): bool
    {
        return $this->subscription()->exists();
    }

    public function services(): Collection
    {
        return Service::query()
            ->whereHas('employees', function ($query) {
                $query->where('user_id', $this->id);
            })
            ->get();
    }

    public function employeePanel(): HasOne
    {
        return $this->hasOne(EmployeePanel::class, 'user_id', 'id');
    }

    public function isAdmin(): bool
    {
        return $this->employeePanel?->role?->nameOrig() === Role::OWNER_PROJECT;
    }


}
