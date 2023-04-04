<?php

namespace App\Modules\Chat\Requests;

use App\Modules\Security\Rules\ValidString;
use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'string',
                'max:1000',
                new ValidString(),
            ],
        ];
    }
}
