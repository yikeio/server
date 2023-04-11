<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'referral_code' => [
                'string',
                'size:6',
                'required',
            ],
        ];
    }
}
