<?php

namespace App\Modules\Payment;

use App\Modules\User\User;

class PaymentPolicy
{
    public function get(User $user, Payment $payment): bool
    {
        return $user->is($payment->creator);
    }
}
