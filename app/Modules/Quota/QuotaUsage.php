<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Jobs\RefreshQuota;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\BelongsToCreator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotaUsage extends Model
{
    use HasSnowflakes;
    use BelongsToCreator;
    use BelongsToQuota;

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

        static::created(function (QuotaUsage $usage) {
            RefreshQuota::dispatchSync($usage->quota);
        });
    }

    public function tokenizable(): BelongsTo
    {
        return $this->morphTo('tokenizable');
    }
}
