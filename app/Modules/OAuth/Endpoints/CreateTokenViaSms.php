<?php

namespace App\Modules\OAuth\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\OAuth\Requests\CreateTokenViaSmsRequest;
use App\Modules\User\User;
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
            abort(404, '手机号未注册');
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
