<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceExchanger extends Model
{
    protected $table = 'service_exchangers';
    protected $fillable = [
        'service_id',
        'exchanger_id',
        'api_key',
        'secret_key',
        'active',
        'balance',
        'fee'
    ];

    public $timestamps = true;

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function exchanger(): BelongsTo
    {
        return $this->belongsTo(Exchanger::class, 'exchanger_id', 'id');
    }
}
