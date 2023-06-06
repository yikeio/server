<?php

namespace App\Modules\Prompt\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePromptRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'required|string',
            'logo' => 'required|string',
            'prompt_cn' => 'required_without:prompt_en|string|max:1200',
            'prompt_en' => 'required_without:prompt_cn|string|max:1200',
            'greeting' => 'required|string|max:1200',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
