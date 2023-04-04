<?php

namespace App\Modules\Service\Sms\Requests;

use App\Modules\Service\Sms\Enums\VerificationCodeScene;
use App\Modules\Service\Sms\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendVerificationCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => [
                'required',
                'string',
                new ValidPhoneNumber(),
            ],
            'scene' => [
                'required',
                'string',
                Rule::enum(VerificationCodeScene::class),
            ],
        ];
    }
}
