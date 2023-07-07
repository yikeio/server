<?php

namespace App\Modules\Auth\Endpoints;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;

class CreatePersonalToken
{
    use ValidatesRequests;

    #[ArrayShape(['value' => "string", 'type' => "string", 'expires_at' => "mixed"])]
    public function __invoke(Request $request): array
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        if ($request->user()->tokens()->where('revoked', false)->count() >= 5) {
            abort(403, '您最多只能创建 5 个 token');
        }

        Passport::personalAccessTokensExpireIn(now()->addYears(5));

        $token = $request->user()->createToken($request->get('name'));

        return [
            'value' => $token->accessToken,
            'type' => 'Bearer',
            'expires_at' => $token->token->expires_at,
        ];
    }
}
