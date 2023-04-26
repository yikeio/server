<?php

namespace App\Modules\Service\Snowflake;

use Godruoyi\Snowflake\Snowflake;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 */
trait HasSnowflakes
{
    public static function bootHasSnowflakes(): void
    {
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), app(Snowflake::class)->id());
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }
}
