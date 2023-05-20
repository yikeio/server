<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Http\Request;

class CreatePrompt
{
    public function __invoke(Request $request): Prompt
    {
        return Prompt::create($request->all())->refresh();
    }
}
