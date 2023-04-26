<?php

namespace App\Modules\Payment\Actions;

use App\Modules\Common\Actions\Action;

class CreatePaymentOrderNumber extends Action
{
    /**
     * @throws \Exception
     */
    public function handle(): string
    {
        return now()->format('ymdHis').random_int(1000, 9999);
    }
}
