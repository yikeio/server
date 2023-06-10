<?php

namespace App\Modules\User\Endpoints;

use App\Modules\User\Requests\UpdateUserRequest;
use Illuminate\Http\Response;

class UpdateUser
{
    public function __invoke(UpdateUserRequest $request): Response
    {
        $request->user()->update($request->validated());

        return response()->noContent();
    }
}
