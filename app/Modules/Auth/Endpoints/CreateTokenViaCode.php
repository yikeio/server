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

        // 按 open_id 和 email 查找用户
        $profile = Profile::query()
            ->where('platform', $driver)
            ->where(function ($query) use ($socialiteUser) {
                $query->where('open_id', $socialiteUser->getId())
                    ->when($socialiteUser->getEmail(), function ($query, $email) {
                        $query->orWhere('email', $email);
                    });
            })
            ->firstOrNew();

        if (!$profile->id) {
            $profile->platform = $driver;
            $profile->open_id = $socialiteUser->getId();
            $profile->raw = $socialiteUser->getRaw();
        }

        $profile->nickname = $socialiteUser->getNickname();
        $profile->name = $socialiteUser->getName();
        $profile->avatar = $socialiteUser->getAvatar();
        $profile->email = $socialiteUser->getEmail();
        $profile->save();

        $user = $profile->user ?? ($socialiteUser->getEmail() ? User::where('email', $socialiteUser->getEmail())->firstOrNew() : new User());

        $user->name ??= $profile->name;
        $user->email ??= $profile->email;
        $user->avatar ??= $profile->avatar;

        if (! $user->id) {
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
