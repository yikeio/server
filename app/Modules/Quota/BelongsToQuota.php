<?php

namespace App\Modules\Quota;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToQuota
{
    public function getQuotaId(): int
    {
        return $this->quota_id;
    }

    public function quota(): BelongsTo
    {
        return $this->belongsTo(Quota::class, 'quota_id', 'id');
    }
}
