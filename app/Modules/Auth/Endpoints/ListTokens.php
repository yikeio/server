<?php

namespace App\Modules\Auth\Endpoints;

use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;

class ListTokens
{
    public function __invoke(Request $request)
    {
        return $request->user()->tokens()
            ->where('name', 'like', '[API]%')
            ->where('revoked', false)
            ->orderByDesc('created_at')
            ->get();
    }
}
