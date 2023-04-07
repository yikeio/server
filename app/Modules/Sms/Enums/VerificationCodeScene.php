<?php

namespace App\Modules\Sms\Enums;

enum VerificationCodeScene: string
{
    case REGISTER = 'register';
    case LOGIN = 'login';
}
