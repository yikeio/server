<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\GiftCard\GiftCard;
use Illuminate\Http\Request;

class CreateGiftCard
{
    public function __invoke(Request $request): GiftCard
    {
        return GiftCard::create($request->all())->refresh();
    }
}
