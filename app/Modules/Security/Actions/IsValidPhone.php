<?php

namespace App\Modules\Security\Actions;

use App\Modules\Common\Actions\Action;
use Illuminate\Support\Str;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class IsValidPhone extends Action
{
    public function handle(string $phone)
    {
        $phone = Str::replace(':', ' ', $phone);

        try {
            $toolkit = PhoneNumberUtil::getInstance();

            $phone = $toolkit->parse($phone);

            if (! $phone) {
                return false;
            }

            $validRegions = config('sms.regions', []);

            $region = $toolkit->getRegionCodeForNumber($phone);

            if (! in_array($region, array_keys($validRegions), true)) {
                return false;
            }

            return $toolkit->isValidNumberForRegion($phone, $region);
        } catch (NumberParseException $e) {
            return false;
        }
    }
}
