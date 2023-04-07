<?php

namespace App\Modules\Payment\Requests;

use App\Modules\Quota\Enums\QuotaType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreatePaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quota_type' => [
                'required',
                'string',
                new Enum(QuotaType::class),
            ],
            'pricing' => [
                'required',
                'string',
                Rule::in(array_keys(config("quota.types.{$this->input('quota_type', 'chat')}.pricings", []))),
            ],
        ];
    }
}
