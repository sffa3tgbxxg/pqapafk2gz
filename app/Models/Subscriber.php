<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscriber extends Model
{
    protected $table = 'subscribers';
    protected $fillable = [
        'user_id',
        'expiry_at',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'expiry_at' => 'datetime',
    ];
    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
