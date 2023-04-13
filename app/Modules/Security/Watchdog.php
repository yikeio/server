<?php

namespace App\Modules\Security;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class Watchdog
{
    protected Request $request;

    public function __construct(protected Application $app)
    {
        $this->request = $app->make(Request::class);
    }

    public function getUserAgent(): ?string
    {
        return $this->app->make(Agent::class)->getUserAgent();
    }

    public function hasValidAuthorizationHeader(): bool
    {
        return ! empty(trim($this->request->bearerToken()));
    }
}
