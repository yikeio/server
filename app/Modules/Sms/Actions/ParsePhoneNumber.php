<?php

namespace App\Modules\Sms\Actions;

use App\Modules\Common\Actions\Action;
use App\Modules\Service\Log\Actions\CreateErrorLog;
use Illuminate\Support\Str;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

class ParsePhoneNumber extends Action
{
    public function handle(string $phoneNumber): PhoneNumber
    {
        $phoneNumber = Str::replace(':', ' ', $phoneNumber);

        $phoneNumberToolkit = PhoneNumberUtil::getInstance();

        try {
            return $phoneNumberToolkit->parse($phoneNumber);
        } catch (NumberParseException $e) {
            CreateErrorLog::run('[SMS] - 解析手机号码失败', [
                'number' => $phoneNumber,
            ], $e);

            throw $e;
        }
    }
}
