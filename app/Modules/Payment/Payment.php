<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property PaymentState $state
 * @property array $processors
 */
class Payment extends Model
{
    use HasSnowflakes;
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'creator_id',
        'amount',
        'number',
        'state',
        'title',
        'gateway',
        'gateway_number',
        'raws',
        'processors',
    ];

    protected $hidden = [
        'raws',
        'processors',
    ];

    protected $casts = [
        'id' => 'string',
        'creator_id' => 'string',
        'state' => PaymentState::class,
        'raws' => 'array',
        'processors' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }
}
