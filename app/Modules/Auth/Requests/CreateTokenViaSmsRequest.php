<?php

namespace App\Modules\Auth\Requests;

use App\Modules\Sms\Enums\VerificationCodeScene;
use App\Modules\Sms\Rules\ValidPhoneNumber;
use App\Modules\Sms\Rules\ValidVerificationCode;
use Illuminate\Foundation\Http\FormRequest;

class CreateTokenViaSmsRequest extends FormRequest
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
                'size:6',
                new ValidVerificationCode(VerificationCodeScene::LOGIN),
            ],
        ];
    }
}
