<?php

namespace App\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|max:45',
            'avatar' => 'string|max:255',
            'referral_code' => 'string|min:5|max:10',
        ];
    }
}
