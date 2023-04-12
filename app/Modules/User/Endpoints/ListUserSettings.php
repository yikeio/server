<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\Enums\SettingKey;
use App\Modules\User\User;
use App\Modules\User\UserSetting;
use Illuminate\Http\Request;

class ListUserSettings extends Endpoint
{
    public function __invoke(Request $request, User $user)
    {
        $this->authorize('get', $user);

        $settings = $user->settings()->get(['value', 'key']);

        $outputs = [];

        foreach (SettingKey::defaults() as $key => $value) {
            $outputs[$key] = $value;
        }

        /** @var UserSetting $setting */
        foreach ($settings as $setting) {
            $outputs[$setting->key->value] = $setting->value;
        }

        return $outputs;
    }
}
