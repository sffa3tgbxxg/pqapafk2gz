<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $table = 'accounts_invoices';
    protected $fillable = ['user_id', 'amount', 'amount_rub', 'requisites', 'status', 'expiry_at'];
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
