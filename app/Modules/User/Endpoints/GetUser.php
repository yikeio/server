<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;

class GetUser extends Endpoint
{
    public function __invoke(Request $request)
    {
        return $request->user()->makeVisible(['email', 'phone_number']);
    }
}
