<?php

namespace App\Modules\User\Enums;

enum SettingKey: string
{
    case CHAT_CONTEXTS_COUNT = 'chat_contexts_count';

    public function rules(): array
    {
        return match ($this) {
            self::CHAT_CONTEXTS_COUNT => [
                'required',
                'integer',
                'between:0,99',
            ],
        };
    }

    public static function defaults(): array
    {
        return [
            self::CHAT_CONTEXTS_COUNT->value => 10,
        ];
    }
}
