<?php

namespace App\Modules\Prompt\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string',
            'description' => 'string',
            'logo' => 'string',
            'prompt_cn' => 'string|max:1200',
            'prompt_en' => 'string|max:1200',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
