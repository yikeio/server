<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Requests\CreatePromptRequest;

class CreatePrompt
{
    public function __invoke(CreatePromptRequest $request)
    {
        abort_if($request->user()->prompts()->count() >= 100, 400, '您无法创建更多的提示');

        if ($request->user()->prompts()->where('name', $request->input('name'))->exists()) {
            abort(400, '已经存在同名场景');
        }

        $prompt = $request->user()->prompts()->create($request->validated());

        if ($request->has('tags')) {
            $prompt->tags()->sync($request->input('tags'));
        }

        return $prompt;
    }
}
