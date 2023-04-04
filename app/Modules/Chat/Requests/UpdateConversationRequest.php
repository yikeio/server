<?php

namespace App\Modules\Chat\Requests;

use App\Modules\Security\Rules\ValidString;
use Illuminate\Foundation\Http\FormRequest;

class UpdateConversationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'string',
                'min:1',
                new ValidString(),
            ],
        ];
    }
}
