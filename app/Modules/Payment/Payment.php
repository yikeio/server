<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Filters\PaymentFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\BelongsToCreator;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property PaymentState $state
 * @property array        $processors
 * @property int          $amount
 * @property int        $number
 */
class Payment extends Model
{
    use HasSnowflakes;
    use HasFactory;
    use Filterable;
    use BelongsToCreator;

    protected $fillable = [
        'creator_id',
        'amount',
        'number',
        'state',
        'title',
        'gateway',
        'gateway_number',
        'raw',
        'processors',
        'context',
        'paid_at',
        'expired_at',
    ];

    protected $hidden = [
        'raw',
        'processors',
    ];

    protected $casts = [
        'id' => 'string',
        'creator_id' => 'string',
        'state' => PaymentState::class,
        'raw' => 'array',
        'processors' => 'array',
        'context' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return PaymentFilter::class;
    }
}
