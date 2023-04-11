<?php

namespace App\Modules\User\Listeners;

use App\Modules\User\Enums\UserSetting;
use App\Modules\User\Events\UserCreated;

class CreateUserSettings
{
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $settings = UserSetting::defaults();

        foreach ($settings as $key => $value) {
            $user->settings()->updateOrCreate([
                'key' => $key,
            ], [
                'value' => $value,
            ]);
        }
    }
}
