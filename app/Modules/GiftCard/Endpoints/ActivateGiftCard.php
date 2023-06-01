<?php

namespace App\Modules\GiftCard\Endpoints;

use App\Modules\GiftCard\Actions\AssignGiftCardToUser;
use App\Modules\GiftCard\GiftCard;
use App\Modules\User\User;
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

        /** @var User $user */
        $user = $request->user();

        $giftCard = GiftCard::query()->where($request->only('code'))->firstOrFail();

        AssignGiftCardToUser::run($giftCard, $user);

        return $giftCard->refresh();
    }
}
