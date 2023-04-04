<?php

namespace App\Modules\Security\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidString implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('输入不能为空');

            return;
        }

        if ($value !== htmlspecialchars($value)) {
            $fail('仅支持输入中英文，数字和常用符号');

            return;
        }

        if ($value !== strip_tags($value)) {
            $fail('仅支持输入中英文，数字和常用符号');

            return;
        }

        if (preg_match('/[^\p{Han}a-zA-Z0-9\s。，、；：？！“”‘’（）【】「」《》…～.,;:?!\'"()\[\]{}~+\-=_<>@#]/u', $value)) {
            $fail('仅支持输入中英文，数字和常用符号');

            return;
        }
    }
}
