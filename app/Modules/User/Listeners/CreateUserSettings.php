<?php

namespace App\Modules\User\Listeners;

use App\Modules\User\Enums\SettingKey;
use App\Modules\User\Events\UserCreated;

class CreateUserSettings
{
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        $settings = SettingKey::defaults();

        foreach ($settings as $key => $value) {
            $user->settings()->updateOrCreate([
                'key' => $key,
            ], [
                'value' => $value,
            ]);
        }
    }
}
