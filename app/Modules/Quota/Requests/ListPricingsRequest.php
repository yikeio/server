<?php

namespace App\Modules\Quota\Requests;

use App\Modules\Quota\Enums\QuotaType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ListPricingsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quota_type' => [
                'required',
                'string',
                new Enum(QuotaType::class),
            ],
        ];
    }
}
