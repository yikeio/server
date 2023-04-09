<?php

namespace App\Modules\Common\Actions;

class ConvertStringToArray extends Action
{
    public function handle(string $string, string $separator = ','): array
    {
        return array_values(array_filter(array_map('trim', explode($separator, $string))));
    }
}
