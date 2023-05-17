<?php

namespace App\Modules\Admin\Endpoints;

use App\Modules\Payment\Payment;
use App\Modules\Prompt\Prompt;
use App\Modules\User\User;

class ListPayments
{
    public function __invoke()
    {
        return Payment::with('creator')->paginate(15);
    }
}
