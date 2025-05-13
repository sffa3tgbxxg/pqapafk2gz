<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'service_id',
        'exchanger_id',
        'external_id',
        'currency_id',
        'user_id',
        'amount_in',
        'amount_out',
        'status',
        'comment',
        'details',
        'expiry_at',
    ];
    public $timestamps = true;

    public const SEARCH = 'search';
    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const PAID_OPERATOR = 'paid_operator';
    public const ERROR = 'error';
    public const CANCEL_USER = 'cancel_user';
    public const CANCEL_OPERATOR = 'cancel_operator';
    public const CANCEL_TIME = 'cancel_time';
    public const CANCEL_SEARCH = 'cancel_search';
    public const CANCEL_INVALID = 'cancel_invalid';
    public const PENDING_CONFIRM = 'pending_confirm';
    public const CANCEL = "cancel";
    public const KYC = "kyc";
    public const PROBLEM = "problem";
    public const CONSIDERATION = "consideration";

    public const STATUSES = [
        self::SEARCH => 'Поиск реквизитов',
        self::PENDING => 'Ожидание оплаты',
        self::PAID => 'Оплачен',
        self::PAID_OPERATOR => 'Оплачен (Оператор)',
        self::ERROR => 'Ошибка',
        self::CANCEL_USER => 'Отменен пользователем',
        self::CANCEL_OPERATOR => 'Отменен (Оператор)',
        self::CANCEL_TIME => 'Отменен по истечению срока оплаты',
        self::CANCEL_SEARCH => 'Реквизиты не найдены',
        self::CANCEL_INVALID => 'Невалидная заявка',
        self::PENDING_CONFIRM => 'Ожидает подтверждения',
        self::CANCEL => 'Отменен',
        self::KYC => 'Нужна верификация (KYC)',
        self::PROBLEM => 'Проблемный платеж',
        self::CONSIDERATION => 'На рассмотрении',
    ];

    public static function getStatusesForOperator(): array
    {
        return [
            self::PAID_OPERATOR => self::STATUSES[self::PAID_OPERATOR],
            self::CANCEL_OPERATOR => self::STATUSES[self::CANCEL_OPERATOR],
            self::KYC => self::STATUSES[self::KYC],
            self::PROBLEM => self::STATUSES[self::PROBLEM],
            self::CONSIDERATION => self::STATUSES[self::CONSIDERATION],
            self::ERROR => self::STATUSES[self::ERROR],
        ];
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => self::STATUSES[$value],
        );
    }

    public function statusOrig(): string
    {
        return $this->attributes['status'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function exchanger(): BelongsTo
    {
        return $this->belongsTo(Exchanger::class, 'exchanger_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserService::class, 'user_id', 'id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function serviceExchanger(): ServiceExchanger
    {
        return ServiceExchanger::query()
            ->where('service_id', $this->attributes['service_id'])
            ->where('exchanger_id', $this->attributes['exchanger_id'])
            ->first();
    }
}
