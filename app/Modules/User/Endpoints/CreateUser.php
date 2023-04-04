<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\Actions\RefreshUserReferrer;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\User;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class CreateUser extends Endpoint
{
    public function __invoke(CreateUserRequest $request)
    {
        $user = User::query()
            ->where('phone_number', $request->input('phone_number'))
            ->first();

        if ($user) {
            abort(422, '手机号已经被使用');
        }

        $user = new User();
        $user->name = '用户-'.Str::substr($request->input('phone_number'), -4);
        $user->phone_number = $request->input('phone_number');
        $user->referral_code = Str::lower(Str::random(6));
        $user->save();

        $referrer = User::query()
            ->where('referral_code', $request->input('referral_code'))
            ->first();

        if (empty($referrer)) {
            abort(422, '邀请码无效');
        }

        RefreshUserReferrer::run($user, $referrer);

        /** @var Agent $agent */
        $agent = app(Agent::class);

        $personalAccessToken = $user->createToken($agent->device() ?: 'unknown');

        return [
            'user' => $user->refresh(),
            'token' => [
                'value' => $personalAccessToken->accessToken,
                'type' => 'Bearer',
                'expires_at' => $personalAccessToken->token->expires_at,
            ],
        ];
    }
}
