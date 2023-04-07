<?php

namespace App\Modules\Payment\Processors;

use App\Modules\Payment\Payment;
use App\Modules\Quota\Actions\GrantUserQuota;
use App\Modules\Quota\Enums\QuotaMeter;
use App\Modules\Quota\Enums\QuotaType;

class GrantQuotaProcessor implements Processor
{
    public function handle(Payment $payment, array $parameters): void
    {
        GrantUserQuota::run($payment->creator, [
            ...$parameters,
            'quota_type' => QuotaType::from($parameters['quota_type']),
            'quota_meter' => QuotaMeter::from($parameters['quota_meter']),
        ]);
    }
}
