<?php

namespace App\Modules\Payment\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Payment\Actions\MarkPaymentAsPaid;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Gateways\GatewayInterface;
use App\Modules\Payment\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProcessPayment extends Endpoint
{
    public function __invoke(Request $request)
    {
        /** @var GatewayInterface $gateway */
        $gateway = app(GatewayInterface::class);

        if (! $gateway->isValidSign($request->input())) {
            return 'failed';
        }

        $gatewayNumber = $gateway->resolveNumber($request->input());

        if (empty($gatewayNumber)) {
            return 'failed';
        }

        $key = "process_gateway_{$gateway->getName()}_gateway_number_$gatewayNumber";

        return Cache::lock($key, 5)
            ->block(2, function () use ($gateway, $gatewayNumber) {
                $payment = Payment::query()
                    ->where([
                        'gateway' => $gateway->getName(),
                        'gateway_number' => $gatewayNumber,
                        'state' => PaymentState::PENDING,
                    ])
                    ->first();

                if (empty($payment)) {
                    return 'failed';
                }

                if (! $gateway->isPaid($gatewayNumber)) {
                    return 'failed';
                }

                MarkPaymentAsPaid::run($payment);

                return 'success';
            });
    }
}
