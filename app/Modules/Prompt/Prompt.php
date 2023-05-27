<?php

namespace App\Modules\Prompt;

use App\Modules\Prompt\Filters\PromptFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\Tag\Tag;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int                                           $id
 * @property string                                        $name
 * @property string                                        $description
 * @property string                                        $logo
 * @property string                                        $prompt_cn
 * @property string                                        $prompt_en
 * @property string                                        $settings
 * @property int                                           $sort_order
 * @property string                                        $deleted_at
 * @property string                                        $created_at
 * @property string                                        $updated_at
 * @property \Illuminate\Database\Eloquent\Collection<Tag> $tags
 * @property int                                           $tags_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Prompt filter(array $input = [], $filter = null)
 */
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

    protected $casts = [
        'settings' => 'array',
    ];

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    protected static function newFactory(): PromptFactory
    {
        return PromptFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return PromptFilter::class;
    }
}
