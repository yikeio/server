<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\GiftCard\GiftCard;
use App\Modules\Payment\Payment;
use App\Modules\Prompt\Prompt;
use App\Modules\User\User;

class ListGiftCards
{
    public function __invoke()
    {
        return GiftCard::with('creator')->paginate(15);
    }
}
