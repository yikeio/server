<?php

namespace App\Modules\GiftCard\Endpoints;

use App\Modules\GiftCard\Actions\AssignGiftCardToUser;
use App\Modules\GiftCard\GiftCard;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class ActivateGiftCard
{
    use ValidatesRequests;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|size:36',
        ]);

        if ((bool) $request->user()->getAvailableQuota()) {
            abort(403, '您还有可用的配额，无法激活礼品卡');
        }

        $giftCard = GiftCard::where('code', $request->input('code'))->firstOrFail();

        AssignGiftCardToUser::run($giftCard, $request->user());

        return $giftCard->refresh();
    }
}
