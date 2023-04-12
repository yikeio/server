<?php

namespace App\Modules\Payment\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Payment;

class MarkPaymentAsExpired extends Action
{
    public function handle(Payment $payment)
    {
        if (! $payment->state->isPending()) {
            return;
        }

        $payment->state = PaymentState::EXPIRED;
        $payment->save();
    }
}
