<?php

namespace App\Modules\Service\OpenAI;

use RuntimeException;

class TokenizerException extends RuntimeException
{
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}
