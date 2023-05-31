<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use Illuminate\Http\Request;

class ListPayments extends Endpoint
{
    public function __invoke(Request $request)
    {
        return $request->user()->payments()->filter($request->query())->get();
    }
}
