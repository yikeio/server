<?php

namespace App\Modules\Service\Snowflake;

use Illuminate\Database\Eloquent\Model;

trait HasSnowflakes
{
    public static function bootHasSnowflakes(): void
    {
        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), app('snowflake')->id());
        });
    }
}
