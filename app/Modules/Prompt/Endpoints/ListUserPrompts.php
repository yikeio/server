<?php

namespace App\Modules\Prompt\Endpoints;

use App\Modules\Prompt\Prompt;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ListUserPrompts
{
    public function __invoke(Request $request): Paginator
    {
        $user = $request->user();

        return Prompt::query()
            ->where(function ($query) use ($user) {
                // 用户创建的
                $query->where('creator_id', $user->id)
                    // 或者用户用过的
                    ->orWhereHas('conversations', function ($query) use ($user) {
                        $query->where('creator_id', $user->id);
                    });
            })
            ->withCount('conversations')
            ->orderByDesc('conversations_count')
            ->orderByDesc('sort_order')
            ->take(100)
            ->filter($request->query())
            ->simplePaginate(100);
    }
}
