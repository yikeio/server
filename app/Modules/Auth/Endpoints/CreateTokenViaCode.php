<?php

namespace App\Modules\Auth\Endpoints;

use App\Modules\Auth\Requests\CreateTokenViaCodeRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Service\State\StateManager;
use App\Modules\User\Profile;
use App\Modules\User\User;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Overtrue\Socialite\Exceptions\AuthorizeFailedException;
use Overtrue\Socialite\SocialiteManager;

class CreateTokenViaCode extends Endpoint
{
    public function __invoke(CreateTokenViaCodeRequest $request): array
    {
        /** @var StateManager $stateManager */
        $stateManager = app(StateManager::class);

        $driver = $stateManager->get($request->input('state'));

        if (empty($driver)) {
            abort(422, '无效的状态值');
        }

        /** @var SocialiteManager $manager */
        $manager = app(SocialiteManager::class);

        try {
            $socialiteUser = $manager->create($driver)->userFromCode($request->input('code'));
        } catch (AuthorizeFailedException $e) {
            abort(403, '授权失败: '.$e->getMessage());
        }

        $query = $socialiteUser->getEmail() ? ['email' => $socialiteUser->getEmail()] : ['platform' => $driver, 'open_id' => $socialiteUser->getId()];

        /** @var Profile $profile */
        $profile = Profile::query()
            ->updateOrCreate($query, [
                'nickname' => $socialiteUser->getNickname(),
                'name' => $socialiteUser->getName(),
                'avatar' => $socialiteUser->getAvatar(),
                'raw' => $socialiteUser->getRaw(),
                'platform' => $driver,
                'open_id' => $socialiteUser->getId(),
            ]);

        $user = $profile->user ?? ($socialiteUser->getEmail() ? User::where('email', $socialiteUser->getEmail())->firstOrNew() : new User());

        $user->name ??= $profile->name;
        $user->email ??= $profile->email;
        $user->avatar ??= $profile->avatar;

        if (!$user->id) {
            $user->referral_code = Str::lower(Str::random(6));
        }

        $user->save();

        if (empty($profile->user)) {
            $profile->user_id = $user->id;
            $profile->save();
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
