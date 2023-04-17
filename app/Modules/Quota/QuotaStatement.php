<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Jobs\RefreshQuota;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotaStatement extends Model
{
    use HasSnowflakes;

    public $incrementing = false;

    protected $fillable = [
        'creator_id',
        'quota_id',
        'tokens_count',
        'tokenizable_type',
        'tokenizable_id',
    ];

    protected $casts = [
        'id' => 'string',
        'creator_id' => 'string',
        'quota_id' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function (QuotaStatement $statement) {
            RefreshQuota::dispatch($statement->quota);
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function quota(): BelongsTo
    {
        return $this->belongsTo(Quota::class, 'quota_id', 'id');
    }

    public function tokenizable(): BelongsTo
    {
        return $this->morphTo('tokenizable');
    }
}
