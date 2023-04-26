<?php

namespace App\Modules\GiftCard\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\GiftCard\GiftCard;
use App\Modules\Payment\Actions\CreatePaymentOrderNumber;
use App\Modules\Payment\Actions\MarkPaymentAsPaid;
use App\Modules\Payment\Enums\Gateway;
use App\Modules\Payment\Enums\PaymentState;
use App\Modules\Payment\Payment;
use App\Modules\Payment\Processors\GrantQuotaProcessor;
use App\Modules\User\User;
use Illuminate\Support\Facades\DB;

class AssignGiftCardToUser extends Action
{
    /**
     * @throws \Exception
     */
    public function handle(GiftCard $card, User $user): Payment
    {
        if ($card->hasUsed()) {
            throw new \Exception('礼品卡已被使用');
        }

        if ($card->hasExpired()) {
            throw new \Exception('礼品卡已过期');
        }

        return DB::transaction(function () use ($card, $user) {
            $card->used_at = now();
            $card->user_id = $user->id;
            $card->save();

            $payment = new Payment();
            $payment->amount = 0;
            $payment->number = CreatePaymentOrderNumber::run();
            $payment->state = PaymentState::PENDING;
            $payment->title = "礼品卡兑换 {$card->name}";
            $payment->gateway = Gateway::GIFT_CARD;
            $payment->gateway_number = $card->id;
            $payment->processors = [
                [
                    'class' => GrantQuotaProcessor::class,
                    'parameters' => [
                        'tokens_count' => $card->tokens_count,
                        'days' => $card->days,
                    ],
                ],
            ];
            $payment->expired_at = now()->addSeconds(60);

            $user->payments()->save($payment);

            MarkPaymentAsPaid::run($payment);

            return $payment;
        });
    }
}
