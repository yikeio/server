<?php

namespace App\Modules\Chat\Requests;

use App\Modules\Security\Rules\ValidString;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'prompt_id' => [
                'nullable',
                Rule::exists('prompts', 'id'),
            ],
        ];
    }
}
