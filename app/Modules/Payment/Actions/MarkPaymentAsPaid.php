<?php

namespace App\Modules\Payment\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Payment;
use App\Modules\Payment\Processors\Processor;
use App\Modules\Reward\Actions\CreateRewardFromPayment;
use App\Modules\User\Actions\RefreshUserPaidTotal;

class MarkPaymentAsPaid extends Action
{
    public function handle(Payment $payment)
    {
        if (! $payment->state->isPending()) {
            return;
        }

        $payment->state = PaymentState::PAID;
        $payment->paid_at = now();
        $payment->save();

        RefreshUserPaidTotal::run($payment->creator);

        // 奖励推荐人
        if ($payment->creator->referrer) {
            CreateRewardFromPayment::run($payment);
        }

        if (! is_array($payment->processors)) {
            return;
        }

        foreach ($payment->processors as $processor) {
            if (! class_exists($processor['class'] ?? '')) {
                continue;
            }

            $instance = new $processor['class']();

            if ($instance instanceof Processor) {
                $instance->handle($payment, $processor['parameters'] ?? []);
            }
        }
    }
}
