<?php

namespace App\Modules\Sms\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Sms\Requests\SendVerificationCodeRequest;
use App\Modules\Sms\VerificationCode;

class SendVerificationCode extends Endpoint
{
    public function __invoke(SendVerificationCodeRequest $request)
    {
        /** @var VerificationCode $verificationCode */
        $verificationCode = app(VerificationCode::class);

        $result = $verificationCode->setPhoneNumber($request->input('phone_number'))
            ->setScene($request->input('scene'))
            ->send();

        if (! $result) {
            abort(500, '发送失败，请稍后再试');
        }

        return response()->noContent();
    }
}
