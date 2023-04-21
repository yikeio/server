<?php

namespace App\Modules\Payment\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Exceptions\GatewayException;
use App\Modules\Payment\Gateways\GatewayInterface;
use App\Modules\Payment\Jobs\MarkPaymentAsExpired;
use App\Modules\Payment\Payment;
use App\Modules\Payment\Requests\CreatePaymentRequest;
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

                if (! empty($user->getAvailableQuota())) {
                    abort(403, '还有未用尽配额，无法购买');
                }

                if ($user->payments()
                    ->where('state', PaymentState::PENDING)
                    ->exists()) {
                    abort(403, '还有未支付的订单，无法购买');
                }

                $pricing = config("quota.pricings.{$request->input('pricing')}");

                if (empty($pricing)) {
                    abort(500, '无定价信息');
                }

                $number = now()->format('ymdHis').random_int(1000, 9999);

                try {
                    /** @var GatewayInterface $gateway */
                    $gateway = app(GatewayInterface::class);

                    $response = $gateway->native([
                        'total_fee' => intval($pricing['price'] * 100),
                        'body' => $pricing['title'],
                        'out_trade_no' => $number,
                    ]);
                } catch (GatewayException $e) {
                    Log::error('[PAYMENT] - 调用支付网关失败', [
                        'pricing' => $pricing,
                        'number' => $number,
                        'exception' => $e,
                    ]);

                    abort(500, '支付网关异常，请稍后再试');
                }

                $payment = new Payment();
                $payment->amount = $pricing['price'];
                $payment->number = $number;
                $payment->state = PaymentState::PENDING;
                $payment->title = $pricing['title'];
                $payment->gateway = $gateway->getName();
                $payment->gateway_number = $gateway->resolveNumber($response);
                $payment->raw = $response;
                $payment->context = $gateway->resolveContext($response);
                $payment->processors = $pricing['processors'];
                $payment->expired_at = now()->addSeconds($gateway->getTtl() - 30);
                $user->payments()->save($payment);

                MarkPaymentAsExpired::dispatch($payment)->delay($payment->expired_at);

                return $payment;
            });
    }
}
