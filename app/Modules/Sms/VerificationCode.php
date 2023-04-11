<?php

namespace App\Modules\Sms;

use App\Modules\Service\Log\Actions\CreateErrorLog;
use App\Modules\Sms\Actions\ParsePhoneNumber;
use Illuminate\Contracts\Cache\Repository;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\PhoneNumber;
use Throwable;

class VerificationCode
{
    protected string $phoneNumber;

    protected string $scene;

    public function __construct(protected EasySms $driver, protected Repository $repository)
    {
    }

    public function send(): bool
    {
        $code = mt_rand(1000, 9999);

        try {
            /** @var \libphonenumber\PhoneNumber $phoneNumber */
            $phoneNumber = ParsePhoneNumber::run($this->phoneNumber);

            $this->driver->send(new PhoneNumber($phoneNumber->getNationalNumber(), $phoneNumber->getCountryCode()), [
                'template' => '1168922',
                'data' => [
                    $code,
                    30,
                ],
            ]);

            return $this->repository->set($this->getCacheKey(), $code, now()->addMinutes(30));
        } catch (Throwable $e) {
            CreateErrorLog::run('[SMS] - 发送验证码失败', [
                'phone_number' => $this->phoneNumber,
                'scene' => $this->scene,
                'code' => $code,
            ], $e);

            return false;
        }
    }

    public function check(string $code): bool
    {
        try {
            $result = strval($this->repository->get($this->getCacheKey())) === $code;

            if ($result) {
                $this->repository->delete($this->getCacheKey());
            }

            return $result;
        } catch (Throwable $e) {
            CreateErrorLog::run('[SMS] - 验证码校验失败', [
                'phone_number' => $this->phoneNumber,
                'scene' => $this->scene,
                'code' => $code,
            ], $e);
        }
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function setScene(string $scene): static
    {
        $this->scene = $scene;

        return $this;
    }

    protected function getCacheKey(): string
    {
        return "sms_verification_code_{$this->scene}_$this->phoneNumber";
    }
}
