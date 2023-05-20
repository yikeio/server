<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\GiftCard\GiftCard;

class ListGiftCards
{
    public function __invoke()
    {
        return GiftCard::with('creator')->paginate(15);
    }
}
