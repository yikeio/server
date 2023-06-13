<?php

namespace App\Modules\Prompt;

use App\Modules\Chat\Conversation;
use App\Modules\Prompt\Filters\PromptFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use App\Modules\Tag\Tag;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property int                                           $creator_id
 *
 * @method static Builder|Prompt filter(array $input = [], $filter = null)
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
        'greeting',
        'settings',
        'sort_order',
    ];

    protected $casts = [
        'id' => 'string',
        'settings' => 'array',
    ];

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    protected static function newFactory(): PromptFactory
    {
        return PromptFactory::new();
    }

    public function greeting(): Attribute
    {
        return new Attribute(get: function ($value) {
            return $value ?: '嗨，欢迎来到一刻，你想聊点什么？';
        });
    }

    public function getModelFilterClass(): string
    {
        return PromptFilter::class;
    }
}
