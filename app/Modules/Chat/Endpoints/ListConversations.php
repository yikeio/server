<?php

namespace App\Modules\Chat\Endpoints;

use App\Modules\Security\Actions\CheckSize;
use Illuminate\Http\Request;

class ListConversations
{
    public function __invoke(Request $request)
    {
        return $request->user()->conversations()
            ->with('prompt')
            ->latest('updated_at')
            ->filter($request->query())
            ->paginate(CheckSize::run($request->query('per_page', 15)));
    }
}
