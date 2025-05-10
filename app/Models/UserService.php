<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    protected $table = 'users_services';
    protected $fillable = ['service_id', 'external_id', 'nickname', 'balance'];
    public $timestamps = true;
}
