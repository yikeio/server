<?php

namespace App\Modules\Quota;

trait Tokenizable
{
    public static function bootTokenizable()
    {
        static::created(function (TokenizableInterface $tokenizable) {
            if ($tokenizable->getTokensCount() <= 0) {
                return;
            }

            $usage = new QuotaUsage();
            $usage->creator_id = $tokenizable->getCreatorId();
            $usage->quota_id = $tokenizable->getQuotaId();
            $usage->tokens_count = $tokenizable->getTokensCount();
            $usage->tokenizable_type = $tokenizable->getTokenizableType();
            $usage->tokenizable_id = $tokenizable->getTokenizableId();
            $usage->save();
        });
    }

    public function getTokenizableId(): int
    {
        return $this->id;
    }

    public function getTokenizableType(): string
    {
        return $this->getMorphClass();
    }

    public function getTokensCount(): int
    {
        return $this->tokens_count ?? 0;
    }
}
