<?php

namespace App\Modules\GiftCard;

use App\Modules\GiftCard\Filters\GiftCardFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\BelongsToCreator;
use App\Modules\User\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property \Carbon\Carbon $expired_at
 * @property \Carbon\Carbon $used_at
 * @property int            $creator_id
 * @property int            $user_id
 * @property int            $tokens_count
 * @property int            $days
 * @property string   $code
 */
class GiftCard extends Model
{
    use HasSnowflakes;
    use HasFactory;
    use Filterable;
    use BelongsToCreator;

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

    protected $appends = [
        'state',
    ];

    protected static function booted()
    {
        static::creating(function (GiftCard $card) {
            $card->code ??= Str::uuid()->toString();
            $card->expired_at ??= now()->addYear();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function hasUsed(): bool
    {
        return ! is_null($this->used_at);
    }

    public function hasExpired(): bool
    {
        return ! is_null($this->expired_at) && $this->expired_at->isPast();
    }

    public function state(): Attribute
    {
        return new Attribute(get: function () {
            if ($this->hasUsed()) {
                return 'used';
            }

            if ($this->hasExpired()) {
                return 'expired';
            }

            return 'pending';
        });
    }

    protected static function newFactory(): GiftCardFactory
    {
        return GiftCardFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return GiftCardFilter::class;
    }
}
