<?php

namespace App\Modules\Quota;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Tokenizable
{
    public static function bootTokenizable()
    {
        static::created(function (TokenizableInterface $tokenizable) {
            if ($tokenizable->getTokensCount() <= 0) {
                return;
            }

            $statment = new QuotaStatement();
            $statment->creator_id = $tokenizable->getCreatorId();
            $statment->quota_id = $tokenizable->getQuotaId();
            $statment->tokens_count = $tokenizable->getTokensCount();
            $tokenizable->quotaStatements()->save($statment);
        });
    }

    public function getTokensCount(): int
    {
        return $this->tokens_count ?? 0;
    }

    public function quotaStatements(): MorphMany
    {
        return $this->morphMany(QuotaStatement::class, 'tokenizable');
    }

    public function quota(): BelongsTo
    {
        return $this->belongsTo(Quota::class, 'quota_id', 'id');
    }
}
