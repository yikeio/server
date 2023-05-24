<?php

namespace App\Modules\Prompt;

use App\Modules\Prompt\Filters\PromptFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prompt extends Model
{
    use SoftDeletes;
    use HasSnowflakes;
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'prompt_cn',
        'prompt_en',
        'settings',
        'sort_order',
    ];

    protected static function newFactory(): PromptFactory
    {
        return PromptFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return PromptFilter::class;
    }
}
