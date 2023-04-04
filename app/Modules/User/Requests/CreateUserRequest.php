<?php

namespace App\Modules\User\Requests;

use App\Modules\Service\Sms\Enums\VerificationCodeScene;
use App\Modules\Service\Sms\Rules\ValidPhoneNumber;
use App\Modules\Service\Sms\Rules\ValidVerificationCode;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => [
                'required',
                'string',
                new ValidPhoneNumber(),
            ],
            'sms_verification_code' => [
                'required',
                'string',
                'size:4',
                new ValidVerificationCode(VerificationCodeScene::REGISTER),
            ],
            'referral_code' => [
                'string',
                'size:6',
                'required',
            ],
        ];
    }
}
