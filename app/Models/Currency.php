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

    const RUBLES = 'RUB';
    const BTC = 'BITCOIN';

    public $timestamps = true;

    public static function getIdByCode($code): ?int
    {
        return self::query()->where('code', $code)?->first()?->id;
    }
}
