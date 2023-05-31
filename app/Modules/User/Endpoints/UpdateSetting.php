<?php

namespace App\Modules\User\Endpoints;

use App\Modules\Common\Endpoints\Endpoint;
use App\Modules\User\Enums\SettingKey;
use Illuminate\Http\Request;

class UpdateSetting extends Endpoint
{
    public function __invoke(Request $request, string $key)
    {
        $key = SettingKey::tryFrom($key);

        if (empty($key)) {
            abort(422, '无效的设置');
        }

        $this->validate($request, [
            'value' => [
                ...$key->rules(),
                'required',
            ],
        ]);

        return $request->user()->settings()->updateOrCreate([
            'key' => $key,
        ], [
            'value' => $request->input('value'),
        ]);
    }
}
