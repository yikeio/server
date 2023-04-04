<?php

namespace App\Modules\Security\Rules;

use App\Modules\Security\Actions\IsValidPhone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/[^+:0-9]/', $value)) {
            $fail('手机号码格式不正确');

            return;
        }

        if (! IsValidPhone::run($value)) {
            $fail('手机号码不合法');
        }
    }
}
