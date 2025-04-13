<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

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

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::ROLES[$value]
        );
    }

    public function nameOrig()
    {
        return $this->attributes['name'];
    }
}
