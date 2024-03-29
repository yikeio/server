<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ListPrompts
{
    public function __invoke(Request $request): Paginator
    {
        return Prompt::query()
            ->withCount('conversations')
            ->orderByDesc('sort_order')
            ->orderByDesc('created_at')
            ->filter($request->query())
            ->simplePaginate(100);
    }
}
