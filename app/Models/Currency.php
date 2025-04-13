<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = [
        'name',
        'code',
        'symbol',
    ];

    public $timestamps = true;
}
