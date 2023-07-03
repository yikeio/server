<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\GiftCard\GiftCard;
use App\Modules\Reward\Reward;
use Illuminate\Http\Request;

class ListRewards
{
    public function __invoke(Request $request)
    {
        return Reward::with(['user', 'fromUser', 'payment'])->filter($request->query())->latest('created_at')->paginate(15);
    }
}
