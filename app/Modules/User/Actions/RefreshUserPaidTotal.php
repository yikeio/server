<?php

namespace App\Modules\User\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\User\User;

class RefreshUserPaidTotal extends Action
{
    public function handle(User $user): void
    {
        $paidTotal = $user->payments()
            ->where('state', PaymentState::PAID)
            ->sum('amount');

        $user->paid_total = $paidTotal;
        $user->timestamps = false;
        $user->save();
    }
}
