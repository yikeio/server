<?php

namespace App\Modules\Sms\Actions;

use App\Modules\Common\Actions\Action;
use Illuminate\Support\Facades\Log;
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
            Log::error('[SMS] - 解析手机号码失败', [
                'phone_number' => $phoneNumber,
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}
