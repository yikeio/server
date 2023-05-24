<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\GiftCard\GiftCard;
use Illuminate\Http\Request;

class ListGiftCards
{
    public function __invoke(Request $request)
    {
        return GiftCard::with('creator')->filter($request->query())->latest('created_at')->paginate(15);
    }
}
