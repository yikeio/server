<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\User\User;
use Illuminate\Http\Request;

class GetUser
{
    public function __invoke(Request $request): User
    {
        return $request->user();
    }
}
