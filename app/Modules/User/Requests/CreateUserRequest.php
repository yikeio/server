<?php

namespace App\Modules\User\Requests;

use App\Modules\Security\Rules\ValidPhone;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
                new ValidPhone(),
            ],
            'referral_code' => [
                'string',
                'size:6',
            ],
        ];
    }
}
