<?php

namespace App\Modules\Security\Actions;

use App\Modules\Common\Actions\Action;
use Vinkla\Hashids\Facades\Hashids;

class EncryptString extends Action
{
    public function handle(string $content): string
    {
        return Hashids::encodeHex(bin2hex($content));
    }
}
