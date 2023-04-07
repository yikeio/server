<?php

namespace App\Modules\Payment\Processors;

use App\Modules\Payment\Payment;

interface Processor
{
    public function handle(Payment $payment, array $parameters): void;
}
