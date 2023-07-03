<?php

namespace App\Modules\Reward\Endpoints;

use App\Modules\Reward\Reward;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ListRewards
{
    public function __invoke(Request $request): LengthAwarePaginator
    {
        return Reward::query()->with([
            'user', 'fromUser'
        ])->whereBelongsTo($request->user())->latest()->paginate(15);
    }
}
