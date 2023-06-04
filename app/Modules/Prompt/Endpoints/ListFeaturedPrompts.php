<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ListFeaturedPrompts
{
    public function __invoke(Request $request): Paginator
    {
        return Prompt::query()
            ->withCount('conversations')
            ->orderByDesc('conversations_count')
            ->orderByDesc('sort_order')
            ->take(100)
            ->filter($request->query())
            ->get();
    }
}
