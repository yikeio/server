<?php

namespace App\Modules\Reward;

use App\Modules\Reward\Enums\RewardState;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                                   $id
 * @property int                                   $user_id
 * @property int                                   $from_user_id
 * @property int                                   $payment_id
 * @property int                                   $amount
 * @property int                                   $rate
 * @property \App\Modules\Reward\Enums\RewardState $state
 * @property User                                  $user
 * @property User                                  $fromUser
 * @property \Carbon\Carbon                        $created_at
 * @property \Carbon\Carbon                        $updated_at
 * @property \Carbon\Carbon                        $deleted_at
 * @property \Carbon\Carbon                        $withdrawn_at
 */
class Reward extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasSnowflakes;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'payment_id',
        'amount',
        'rate',
        'state',
    ];

    protected $casts = [
        'state' => RewardState::class,
        'amount' => 'int',
        'rate' => 'int',
        'withdrawn_at' => 'timestamp',
        'payment_id' => 'int',
        'user_id' => 'int',
        'from_user_id' => 'int',
    ];

    protected $with = ['fromUser'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id')->withTrashed();
    }

    protected static function newFactory(): RewardFactory
    {
        return RewardFactory::new();
    }
}
