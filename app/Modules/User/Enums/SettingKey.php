<?php

namespace App\Modules\User\Enums;

use Illuminate\Validation\Rule;

enum SettingKey: string
{
    case CHAT_CONTEXTS_COUNT = 'chat_contexts_count';
    case CHAT_SUBMIT_KEY = 'chat_submit_key';
    case CHAT_FONT_SIZE = 'chat_font_size';
    case CHAT_BUBBLE = 'chat_bubble';
    case AVATAR = 'avatar';
    case THEME = 'theme';
    case NO_BORDER = 'no_border';

    public function description(): string
    {
        return match ($this) {
            self::CHAT_CONTEXTS_COUNT => '携带上下文数量',
            self::CHAT_SUBMIT_KEY => '发送消息快捷键',
            self::CHAT_FONT_SIZE => '聊天字体大小',
            self::CHAT_BUBBLE => '聊天气泡',
            self::AVATAR => '头像',
            self::THEME => '主题',
            self::NO_BORDER => '无边框',
        };
    }

    public function rules(): array
    {
        return match ($this) {
            self::CHAT_CONTEXTS_COUNT => [
                'integer',
                'between:0,99',
            ],
            self::CHAT_SUBMIT_KEY => [
                'string',
                Rule::in([
                    'Enter',
                    'Ctrl + Enter',
                    'Shift + Enter',
                    'Alt + Enter',
                    'Meta + Enter',
                ]),
            ],
            self::CHAT_FONT_SIZE => [
                'integer',
                'between:12,18',
            ],
            self::THEME => [
                'string',
                Rule::in([
                    'auto',
                    'light',
                    'dark',
                ]),
            ],
            self::NO_BORDER, self::CHAT_BUBBLE => [
                'boolean',
            ],
            self::AVATAR => [
                'string',
                'regex:/^[0-9a-z]{5}$/',
            ],
        };
    }

    public static function defaults(): array
    {
        return [
            self::CHAT_CONTEXTS_COUNT->value => 10,
            self::CHAT_SUBMIT_KEY->value => 'Enter',
            self::CHAT_FONT_SIZE->value => 14,
            self::CHAT_BUBBLE->value => false,
            self::AVATAR->value => '1f603',
            self::THEME->value => 'auto',
            self::NO_BORDER->value => true,
        ];
    }
}
