<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $table = 'accounts_invoices';
    protected $fillable = ['user_id', 'amount', 'ex_rate', 'currency_id', 'address_id', 'status', 'expiry_at'];
    protected $casts = [
        'user_id' => 'integer',
        'amount_rub' => 'float',
        'amount' => 'string',
        'requisites' => 'string',
        'status' => 'string',
        'expiry_at' => 'datetime'
    ];

    public const PENDING = 'pending';
    public const CANCELED = 'canceled';
    public const PAID = 'paid';

    public $timestamps = true;

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => __("statuses.{$value}", locale: 'ru'),
        );
    }

    public function statusOrig(): string
    {
        return $this->attributes['status'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(CryptoAddress::class, 'address_id', 'id');
    }

    public function requisites(): string
    {
        return $this->address?->address ?? "";
    }
}
