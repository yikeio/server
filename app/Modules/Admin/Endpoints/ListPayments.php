<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Payment\Payment;

class ListPayments
{
    public function __invoke()
    {
        return Payment::with('creator')->paginate(15);
    }
}
