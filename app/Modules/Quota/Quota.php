<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Enums\QuotaMeter;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property QuotaMeter $meter
 * @property QuotaType $type
 * @property array $usage
 */
class Quota extends Model
{
    use HasSnowflakes;
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'is_available',
        'type',
        'meter',
        'usage',
        'expired_at',
        'tokens_count',
        'used_tokens_count',
        'available_tokens_count',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'expired_at' => 'datetime',
        'meter' => QuotaMeter::class,
        'type' => QuotaType::class,
        'usage' => 'array',
        'is_available' => 'boolean',
    ];

    protected $appends = [
        'is_expired',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->isExpired();
    }

    public function isExpired(): bool
    {
        return (bool) $this->expired_at?->isPast();
    }
}
