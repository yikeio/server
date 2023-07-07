<?php

namespace App\Modules\Auth\Endpoints;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\Token;

class RevokeToken
{
    public function __invoke(Request $request, Token $token): Response
    {
        abort_if($token->user_id != $request->user()->id, 403, '非法操作');

        $token->revoke();

        return response()->noContent();
    }
}
