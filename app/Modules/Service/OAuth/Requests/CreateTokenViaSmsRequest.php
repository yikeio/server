<?php

namespace App\Modules\Service\OAuth\Requests;

use App\Modules\Service\Sms\Enums\VerificationCodeScene;
use App\Modules\Service\Sms\Rules\ValidPhoneNumber;
use App\Modules\Service\Sms\Rules\ValidVerificationCode;
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
                'size:4',
                new ValidVerificationCode(VerificationCodeScene::LOGIN),
            ],
        ];
    }
}
