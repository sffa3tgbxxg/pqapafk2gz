<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PriceSubscription extends Model
{
    protected $table = 'price_subscription';
    protected $fillable = [
        'price_rub',
    ];
    public $timestamps = false;

    public static function getPrice(): float
    {
        return Cache::remember('price_subscription', 60 * 60, function () {
            return (float)self::query()->first()?->price_rub;
        });
    }
}
