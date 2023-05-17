<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeletePrompt
{
    public function __invoke(Request $request, Prompt $prompt): Response
    {
        $prompt->delete();

        return response()->noContent();
    }
}
