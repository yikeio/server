<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Payment\Payment;
use Illuminate\Http\Request;

class ListPayments
{
    public function __invoke(Request $request)
    {
        return Payment::with('creator')->filter($request->query())->latest('created_at')->paginate(15);
    }
}
