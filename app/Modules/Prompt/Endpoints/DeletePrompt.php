<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;
use App\Modules\Prompt\Requests\CreatePromptRequest;
use Illuminate\Http\Request;

class DeletePrompt
{
    public function __invoke(Request $request, Prompt $prompt)
    {
        abort_if($prompt->creator_id != $request->user()->id, 403, '您无权删除该场景');

        $prompt->delete();

        return response()->noContent();
    }
}
