<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;
use App\Modules\Prompt\Requests\CreatePromptRequest;
use App\Modules\Prompt\Requests\UpdatePromptRequest;

class UpdatePrompt
{
    public function __invoke(UpdatePromptRequest $request, Prompt $prompt): Prompt
    {
        abort_if($prompt->creator_id != $request->user()->id, 403, '您无权修改该场景');

        if ($request->user()->prompts()->whereNot('id', $prompt->id)->where('name', $request->input('name'))->exists()) {
            abort(400, '已经存在同名场景');
        }

        $prompt->update($request->validated());

        if ($request->has('tags')) {
            $prompt->tags()->sync($request->input('tags'));
        }

        return $prompt;
    }
}
