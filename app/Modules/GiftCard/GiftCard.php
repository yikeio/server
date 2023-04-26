<?php

namespace App\Modules\GiftCard;

use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $used_at
 * @property int            $user_id
 * @property int            $tokens_count
 * @property int            $days
 * @property string   $code
 */
class GiftCard extends Model
{
    use HasSnowflakes;
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'used_at',
        'expired_at',
        'tokens_count',
        'days',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (GiftCard $card) {
            $card->code ??= \Illuminate\Support\Str::uuid()->toString();
            $card->expired_at ??= now()->addYear();
        });
    }

    public function hasUsed(): bool
    {
        return ! is_null($this->used_at);
    }

    public function hasExpired(): bool
    {
        return ! is_null($this->expired_at) && $this->expired_at->isPast();
    }

    protected static function newFactory(): GiftCardFactory
    {
        return GiftCardFactory::new();
    }
}
