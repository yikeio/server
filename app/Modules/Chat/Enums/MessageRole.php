<?php

namespace App\Modules\Chat\Enums;

enum MessageRole: string
{
    case USER = 'user';
    case SYSTEM = 'system';
    case ASSISTANT = 'assistant';
}
