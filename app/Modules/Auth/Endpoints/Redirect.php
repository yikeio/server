<?php

namespace App\Modules\Auth\Endpoints;

use App\Modules\Auth\Requests\RedirectRequest;
use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\Service\State\StateManager;
use Overtrue\Socialite\SocialiteManager;

class Redirect extends Endpoint
{
    public function __invoke(RedirectRequest $request)
    {
        /** @var SocialiteManager $manager */
        $manager = app(SocialiteManager::class);

        /** @var StateManager $stateManager */
        $stateManager = app(StateManager::class);

        $stateKey = $stateManager->create($request->input('driver'));

        return redirect($manager->create($request->input('driver'))->withState($stateKey)->redirect());
    }
}
