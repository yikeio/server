<?php

namespace App\Modules\Quota\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Quota\QuotaUsage;
use App\Modules\Quota\TokenizableInterface;

class CreateQuotaUsage extends Action
{
    public function handle(TokenizableInterface $tokenizable): QuotaUsage
    {
        $usage = new QuotaUsage();
        $usage->creator_id = $tokenizable->getCreatorId();
        $usage->quota_id = $tokenizable->getQuotaId();
        $usage->tokens_count = $tokenizable->getTokensCount();
        $usage->tokenizable_type = $tokenizable->getTokenizableType();
        $usage->tokenizable_id = $tokenizable->getTokenizableId();
        $usage->save();

        return $usage;
    }
}
