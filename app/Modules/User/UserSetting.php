<?php

namespace App\Modules\User;

use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Enums\UserSetting $key
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
        'key' => Enums\UserSetting::class,
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
