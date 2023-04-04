<?php

namespace App\Modules\Service\Sms\Rules;

use App\Modules\Service\Sms\Actions\IsValidPhoneNumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/[^+:0-9]/', $value)) {
            $fail('手机号码格式不正确');

            return;
        }

        if (! IsValidPhoneNumber::run($value)) {
            $fail('手机号码不合法');
        }
    }
}
