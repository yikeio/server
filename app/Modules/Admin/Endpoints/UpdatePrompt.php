<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Http\Request;

class UpdatePrompt
{
    public function __invoke(Request $request, Prompt $prompt): Prompt
    {
        $prompt->update($request->all());

        return $prompt;
    }
}
