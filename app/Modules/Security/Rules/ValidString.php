<?php

namespace App\Modules\Security\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidString implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('仅支持输入字符串');

            return;
        }

        if (empty($value)) {
            return;
        }

        // https://www.php.net/manual/zh/regexp.reference.unicode.php
        // Han 匹配所有汉字
        // P 匹配所有标点符号
        // S 匹配所有符号
        // Z 匹配所有分隔符
        if (preg_match('/[^a-zA-Z0-9\s\p{Han}\p{P}\p{S}\p{Z}]/u', $value)) {
            $fail('仅支持输入中英文，数字和常用符号');
        }
    }
}
