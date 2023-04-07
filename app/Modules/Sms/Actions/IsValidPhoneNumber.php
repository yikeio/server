<?php

namespace App\Modules\Sms\Actions;

use App\Modules\Common\Actions\Action;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class IsValidPhoneNumber extends Action
{
    public function handle(string $phoneNumber)
    {
        try {
            $phoneNumber = ParsePhoneNumber::run($phoneNumber);
        } catch (NumberParseException $e) {
            return false;
        }

        $phoneNumberToolkit = PhoneNumberUtil::getInstance();

        $validRegions = config('sms.regions', []);

        $region = $phoneNumberToolkit->getRegionCodeForNumber($phoneNumber);

        if (! in_array($region, array_keys($validRegions), true)) {
            return false;
        }

        return $phoneNumberToolkit->isValidNumberForRegion($phoneNumber, $region);
    }
}
