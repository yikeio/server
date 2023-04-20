<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTokenViaCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
            ],
            'state' => [
                'required',
                'string',
                'size:40',
            ],
        ];
    }
}
