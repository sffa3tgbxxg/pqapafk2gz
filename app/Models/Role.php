<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name'];

    public $timestamps = true;

    public const ROLES = [
        'owner_project' => 'Администратор проекта',
        'owner_service' => 'Владелец',
        'admin_service' => 'Администратор',
        'operator_service' => 'Оператор'
    ];

    public const OWNER_PROJECT = 'owner_project';
    public const OWNER_SERVICE = 'owner_service';
    public const ADMIN_SERVICE = 'admin_service';
    public const OPERATOR_SERVICE = 'operator_service';

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::ROLES[$value]
        );
    }

    public static function getByName(string $name): string
    {
        return self::ROLES[$name] ?? '';
    }

    public function nameOrig()
    {
        return $this->attributes['name'];
    }

    public static function getIdByName(string $name): int
    {
        return Cache::remember("role_id:{$name}", 60 * 60, function () use ($name) {
            return self::query()->where('name', $name)->first()->id;
        });
    }
}
