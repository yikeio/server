<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Http\Request;

class GetPrompt
{
    public function __invoke(Request $request, Prompt $prompt): Prompt
    {
        return $prompt;
    }
}
