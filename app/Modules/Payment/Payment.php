<?php

namespace App\Modules\Payment;

use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Filters\PaymentFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use EloquentFilter\Filterable;
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
    use Filterable;

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
        'context',
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
        'context' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    protected static function newFactory(): PaymentFactory
    {
        return PaymentFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return PaymentFilter::class;
    }
}
