<?php

namespace App\Modules\Sms\Requests;

use App\Modules\Sms\Enums\VerificationCodeScene;
use App\Modules\Sms\Rules\ValidPhoneNumber;
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
