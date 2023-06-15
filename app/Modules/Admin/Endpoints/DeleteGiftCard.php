<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\GiftCard\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteGiftCard
{
    public function __invoke(Request $request, GiftCard $giftCard): Response
    {
        $giftCard->delete();

        return response()->noContent();
    }
}
