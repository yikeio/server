<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\GiftCard\GiftCard;
use Illuminate\Http\Request;

class UpdateGiftCard
{
    public function __invoke(Request $request, GiftCard $giftCard): GiftCard
    {
        $giftCard->update($request->all());

        return $giftCard;
    }
}
