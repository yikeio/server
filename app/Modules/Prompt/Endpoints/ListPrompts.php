<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ListPrompts
{
    public function __invoke(Request $request): Paginator
    {
        return Prompt::filter($request->query())->simplePaginate(100);
    }
}
