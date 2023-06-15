<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Http\Request;

class ListPrompts
{
    public function __invoke(Request $request)
    {
        return Prompt::with('tags')->withCount('conversations')->latest('created_at')->filter($request->query())->paginate(15);
    }
}
