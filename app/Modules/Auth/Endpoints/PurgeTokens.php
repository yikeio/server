<?php

namespace App\Modules\Auth\Endpoints;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Token;
use Laravel\Passport\TokenRepository;

class PurgeTokens
{
    public function __invoke(Request $request): Response
    {
        $request->user()->tokens()->get()->each(fn(Token $token) => $token->revoke());

        return response()->noContent();
    }
}
