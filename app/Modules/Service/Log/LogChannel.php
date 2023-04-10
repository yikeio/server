<?php

namespace App\Modules\Service\Log;

enum LogChannel: string
{
    case PAYMENT = 'payment';
    case OPENAI = 'openai';
    case REQUEST = 'request';
}
