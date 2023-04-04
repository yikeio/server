<?php

namespace App\Modules\Service\Sms\Enums;

enum VerificationCodeScene: string
{
    case REGISTER = 'register';
    case LOGIN = 'login';
}
