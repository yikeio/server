<?php

namespace App\Modules\Quota;

use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $tokens_count
 * @property int $used_tokens_count
 * @property int $available_tokens_count
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
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'expired_at' => 'datetime',
        'usage' => 'array',
        'is_available' => 'boolean',
    ];

    protected $appends = [
        'is_expired',
        'available_tokens_count',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function statements(): HasMany
    {
        return $this->hasMany(QuotaStatement::class, 'quota_id', 'id');
    }

    public function getAvailableTokensCountAttribute(): int
    {
        return $this->tokens_count - $this->used_tokens_count;
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
