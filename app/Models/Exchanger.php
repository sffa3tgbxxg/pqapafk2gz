<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchanger extends Model
{
    protected $table = 'exchangers';
    protected $fillable = [
        'name', 'fee', 'auto_withdraw', 'min_amount', 'max_amount', 'min_withdraw', 'endpoint', 'image', 'active'
    ];

    public $timestamps = true;
}
