<?php

namespace App\Modules\Auth\Endpoints;

use App\Modules\Auth\Requests\CreateTokenViaSmsRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\User;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class CreateTokenViaSms extends Endpoint
{
    public function __invoke(CreateTokenViaSmsRequest $request)
    {
        /** @var User $user */
        $user = User::query()
            ->where('phone_number', $request->input('phone_number'))
            ->first();

        if (empty($user)) {
            $user = new User();
            $user->name = '用户-'.Str::substr($request->input('phone_number'), -4);
            $user->phone_number = $request->input('phone_number');
            $user->referral_code = Str::lower(Str::random(6));
            $user->save();
        }

        /** @var Agent $agent */
        $agent = app(Agent::class);

        $personalAccessToken = $user->createToken($agent->device() ?: 'unknown');

        return [
            'value' => $personalAccessToken->accessToken,
            'type' => 'Bearer',
            'expires_at' => $personalAccessToken->token->expires_at,
        ];
    }
}
