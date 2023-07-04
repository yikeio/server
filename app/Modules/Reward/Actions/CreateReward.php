<?php

namespace App\Modules\Reward\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Payment\Payment;
use App\Modules\Reward\Events\RewardCreated;
use App\Modules\User\User;

class CreateReward extends Action
{
    public function handle(User $user, User $fromUser, Payment $payment, int $rate)
    {
        // 最大 50% 分成
        $rate = max(0, min(50, $rate));

        $amount = round($payment->amount * $rate / 100, 2);

        if ($amount <= 0) {
            return null;
        }

        /** @var \App\Modules\Reward\Reward $reward */
        $reward = $user->rewards()->firstOrCreate([
            'from_user_id' => $fromUser->id,
            'payment_id' => $payment->id,
        ], [
            'amount' => $amount,
            'rate' => $rate,
        ]);

        event(new RewardCreated($reward));

        return $reward;
    }
}
