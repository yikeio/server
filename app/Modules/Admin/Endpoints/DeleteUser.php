<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteUser
{
    public function __invoke(Request $request, User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }
}
