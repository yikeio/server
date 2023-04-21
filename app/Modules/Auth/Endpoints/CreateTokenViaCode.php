<?php

namespace App\Modules\Auth\Endpoints;

use App\Modules\Auth\Requests\CreateTokenViaCodeRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Service\State\StateManager;
use App\Modules\User\Profile;
use App\Modules\User\User;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Overtrue\Socialite\SocialiteManager;

class CreateTokenViaCode extends Endpoint
{
    public function __invoke(CreateTokenViaCodeRequest $request)
    {
        /** @var StateManager $stateManager */
        $stateManager = app(StateManager::class);

        $driver = $stateManager->get($request->input('state'));

        if (empty($driver)) {
            abort(422, '无效的状态值');
        }

        /** @var SocialiteManager $manager */
        $manager = app(SocialiteManager::class);

        $user = $manager->create($driver)->userFromCode($request->input('code'));

        /** @var Profile $profile */
        $profile = Profile::query()
            ->updateOrCreate([
                'platform' => $driver,
                'open_id' => $user->getId(),
            ], [
                'nickname' => $user->getNickname(),
                'name' => $user->getName(),
                'avatar' => $user->getAvatar(),
                'raw' => $user->getRaw(),
            ]);

        if (empty($profile->user_id)) {
            /** @var User $user */
            $user = User::query()->create([
                'name' => $user->getName(),
                'referral_code' => Str::lower(Str::random(6)),
            ]);

            $profile->user_id = $user->id;
            $profile->save();
        } else {
            $user = $profile->user;
            $user->name = $profile->name;
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
