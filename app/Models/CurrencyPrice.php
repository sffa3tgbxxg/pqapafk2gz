<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CurrencyPrice extends Model
{
    protected $table = 'currency_prices';
    protected $fillable = [
        'currency_id',
        'price',
    ];

    public $timestamps = true;

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
