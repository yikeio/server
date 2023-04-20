<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RedirectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driver' => [
                'required',
                'string',
                Rule::in(array_keys(config('socialite'))),
            ],
        ];
    }
}
