<?php

namespace App\Modules\Payment\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Exceptions\GatewayException;
use App\Modules\Payment\Gateways\GatewayInterface;
use App\Modules\Payment\Payment;
use App\Modules\Payment\Requests\CreatePaymentRequest;
use App\Modules\Quota\Enums\QuotaType;
use App\Modules\User\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CreatePayment extends Endpoint
{
    public function __invoke(CreatePaymentRequest $request): Payment
    {
        $key = "user_{$request->user()->id}_create_payment";

        return Cache::lock($key, 10)
            ->block(5, function () use ($request) {
                /** @var User $user */
                $user = $request->user();

                $quotaType = QuotaType::from($request->input('quota_type'));

                if (! empty($user->getQuota($quotaType))) {
                    abort(403, '还有未用尽配额，无法购买');
                }

                if ($user->payments()
                    ->where('state', PaymentState::PENDING)
                    ->exists()) {
                    abort(403, '还有未支付的订单，无法购买');
                }

                $pricing = config("quota.types.{$quotaType->value}.pricings.{$request->input('pricing')}");

                $number = now()->format('ymdHis').random_int(1000, 9999);

                try {
                    /** @var GatewayInterface $gateway */
                    $gateway = app(GatewayInterface::class);

                    $response = $gateway->native([
                        'total_fee' => $pricing['price'],
                        'body' => $pricing['title'],
                        'out_trade_no' => $number,
                    ]);
                } catch (GatewayException $e) {
                    Log::channel('pay')->error($e->getMessage());
                    abort(500, '支付网关异常，请稍后再试');
                }

                $payment = new Payment();
                $payment->amount = $pricing['price'];
                $payment->number = $number;
                $payment->state = PaymentState::PENDING;
                $payment->title = $pricing['title'];
                $payment->gateway = $gateway->getName();
                $payment->gateway_number = $gateway->resolveNumber($response);
                $payment->raws = $response;
                $payment->processors = $pricing['processors'];
                $user->payments()->save($payment);

                return $payment;
            });
    }
}
