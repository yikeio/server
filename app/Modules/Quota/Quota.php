<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Enums\QuotaType;
use App\Modules\Quota\Meters\Meter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property QuotaType $type
 * @property array $usage
 */
class Quota extends Model
{
    use HasSnowflakes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_available',
        'type',
        'usage',
        'expired_at',
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'expired_at' => 'datetime',
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
        return (bool) $this->expired_at?->isPast();
    }

    public function getMeter(): Meter
    {
        return $this->type->getMeter($this->usage ?? []);
    }
}
