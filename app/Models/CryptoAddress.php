<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoAddress extends Model
{
    protected $table = 'crypto_addresses';
    protected $fillable = [
        'address',
        'mnemonic',
        'balance',
        'currency_id',
    ];
    public $timestamps = true;

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
