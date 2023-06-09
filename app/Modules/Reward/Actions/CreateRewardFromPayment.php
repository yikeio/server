<?php

namespace App\Modules\Reward\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Payment\Payment;

class CreateRewardFromPayment extends Action
{
    /**
     * @throws \Exception
     */
    public function handle(Payment $payment): void
    {
        if ($payment->creator->referrer) {
            CreateReward::run($payment->creator->referrer, $payment->creator, $payment, config('payment.reward.rate.to_referrer'));
        }

        CreateReward::run($payment->creator, $payment->creator, $payment, config('payment.reward.rate.to_self'));
    }
}
