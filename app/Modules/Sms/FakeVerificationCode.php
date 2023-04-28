<?php

namespace App\Modules\Sms;

class FakeVerificationCode extends VerificationCode
{
    public function send(): bool
    {
        return $this->repository->set($this->getCacheKey(), 666666, now()->addMinutes(30));
    }
}
