<?php

namespace App\Modules\Chat\Exceptions;

use RuntimeException;

class InvokeTokenizerException extends RuntimeException
{
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}
