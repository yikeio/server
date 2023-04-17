<?php

namespace App\Modules\Quota\Listeners;

use App\Modules\Quota\Actions\ConsumeUserQuota as ConsumeUserQuotaAction;
use App\Modules\Quota\ConsumeQuotaEvent;
use Illuminate\Support\Facades\Log;

class ConsumeUserQuota
{
    public function handle(ConsumeQuotaEvent $event)
    {
        if (empty($event->getCreator())) {
            return;
        }

        Log::info('[QUOTA] - 计费事件触发成功', [
            'event' => get_class($event),
            'creator_id' => $event->getCreator()->id,
            'quota_type' => $event->getQuotaType(),
            'usage' => $event->getUsage(),
        ]);

        ConsumeUserQuotaAction::run($event->getQuota(), $event->getTokensCount());
    }
}
