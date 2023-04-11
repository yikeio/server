<?php

namespace App\Modules\User;

use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\User\Enums\SettingKey;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property SettingKey $key
 */
class UserSetting extends Model
{
    use HasSnowflakes;

    protected $fillable = [
        'user_id',
        'key',
        'value',
    ];

    protected $casts = [
        'key' => SettingKey::class,
    ];

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $value = json_decode($value, true);

                if (array_keys($value) === ['value']) {
                    $value = $value['value'];
                }

                return $value;
            },
            set: function ($value) {
                if (! is_array($value)) {
                    $value = [
                        'value' => $value,
                    ];
                }

                return json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        );
    }
}
