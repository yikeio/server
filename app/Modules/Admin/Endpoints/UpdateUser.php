<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\User\User;
use Illuminate\Http\Request;

class UpdateUser
{
    public function __invoke(Request $request, User $user): User
    {
        $user->update($request->all());

        return $user;
    }
}
