<?php

namespace App\Modules\Security\Actions;

use App\Modules\Common\Actions\Action;

class CheckSize extends Action
{
    public function handle(mixed $value, int $max = 100)
    {
        if (is_int($value)) {
            return min($value, $max);
        }

        return $value;
    }
}
