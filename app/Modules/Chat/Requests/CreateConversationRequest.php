<?php

namespace App\Modules\Chat\Requests;

use App\Modules\Security\Rules\ValidString;
use Illuminate\Foundation\Http\FormRequest;

class CreateConversationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:1',
                new ValidString(),
            ],
        ];
    }
}
