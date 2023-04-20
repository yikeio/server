<?php

namespace App\Modules\Auth\Endpoints;

use App\Modules\Auth\Requests\CreateTokenViaCodeRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Service\State\StateManager;
use Illuminate\Http\Request;
use Overtrue\Socialite\SocialiteManager;

class CreateTokenViaCode extends Endpoint
{
    public function __invoke(CreateTokenViaCodeRequest $request)
    {
        /** @var SocialiteManager $manager */
        $manager = app(SocialiteManager::class);

        /** @var StateManager $stateManager */
        $stateManager = app(StateManager::class);

        $driver = $stateManager->get($request->input('state'));

        if (empty($driver)) {
            abort(422, '无效的状态值');
        }

        $user = $manager->create($driver)->userFromCode($request->input('code'));
    }
}
