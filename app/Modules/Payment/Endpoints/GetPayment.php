<?php

namespace App\Modules\Payment\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Payment\Payment;
use Illuminate\Http\Request;

class GetPayment extends Endpoint
{
    public function __invoke(Request $request, Payment $payment): Payment
    {
        $this->authorize('get', $payment);

        return $payment;
    }
}
