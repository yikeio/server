<?php

namespace App\Modules\Payment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pricing' => [
                'required',
                'string',
                Rule::in(array_keys(config('quota.pricings', []))),
            ],
        ];
    }
}
