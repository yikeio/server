<?php

namespace App\Modules\Security\Actions;

use App\Modules\Common\Actions\Action;
use Vinkla\Hashids\Facades\Hashids;

class DecryptString extends Action
{
    public function handle(string $content): string
    {
        return hex2bin(Hashids::decodeHex($content));
    }
}
