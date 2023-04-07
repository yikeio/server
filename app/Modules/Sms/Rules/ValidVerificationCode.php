<?php

namespace App\Modules\Sms\Rules;

use App\Modules\Sms\Enums\VerificationCodeScene;
use App\Modules\Sms\VerificationCode;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidVerificationCode implements ValidationRule, DataAwareRule
{
    protected array $data;

    public function __construct(protected VerificationCodeScene $scene)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var VerificationCode $verificationCode */
        $verificationCode = app(VerificationCode::class);

        $result = $verificationCode->setPhoneNumber($this->data['phone_number'])
            ->setScene($this->scene->value)
            ->check($value);

        if (! $result) {
            $fail('验证码错误');
        }
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
