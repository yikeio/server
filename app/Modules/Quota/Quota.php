<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Enums\QuotaState;
use App\Modules\Quota\Filters\QuotaFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $tokens_count
 * @property int $used_tokens_count
 * @property int $available_tokens_count
 * @property array $usage
 * @property QuotaState $state
 */
class Quota extends Model
{
    use HasSnowflakes;
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'user_id',
        'is_available',
        'usage',
        'expired_at',
        'tokens_count',
        'used_tokens_count',
        'state',
        'days',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'expired_at' => 'datetime',
        'usage' => 'array',
        'is_available' => 'boolean',
        'state' => QuotaState::class,
    ];

    protected $appends = [
        'available_tokens_count',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(QuotaUsage::class, 'quota_id', 'id');
    }

    public function getAvailableTokensCountAttribute(): int
    {
        return $this->tokens_count - $this->used_tokens_count;
    }

    public function getModelFilterClass(): string
    {
        return QuotaFilter::class;
    }

    protected static function newFactory()
    {
        return QuotaFactory::new();
    }
}
