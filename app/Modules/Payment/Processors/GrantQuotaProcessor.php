<?php

namespace App\Modules\Payment\Processors;

use App\Modules\Payment\Payment;
use App\Modules\Quota\Actions\GrantUserQuota;

class GrantQuotaProcessor implements Processor
{
    public function handle(Payment $payment, array $parameters): void
    {
        GrantUserQuota::run($payment->creator, $parameters);
    }
}
