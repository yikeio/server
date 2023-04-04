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
            ->where('phone', $request->input('phone'))
            ->first();

        if ($user) {
            abort(400, '手机号已经被使用');
        }

        $user = new User();
        $user->name = '用户-'.Str::substr($request->input('phone'), -4);
        $user->phone = $request->input('phone');
        $user->referral_code = Str::lower(Str::random(6));
        $user->save();

        if (! empty($request->input('referral_code'))) {
            $referrer = User::query()
                ->where('referral_code', $request->input('referral_code'))
                ->first();

            if ($referrer) {
                RefreshUserReferrer::run($user, $referrer);
            }
        }

        /** @var Agent $agent */
        $agent = app(Agent::class);

        $token = $user->createToken($agent->device() ?: 'unknown');

        return [
            'user' => $user->refresh(),
            'token' => [
                'value' => $token->accessToken,
                'type' => 'Bearer',
                'expires_at' => $token->token->expires_at,
            ],
        ];
    }
}
