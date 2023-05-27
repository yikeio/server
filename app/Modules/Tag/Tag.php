<?php

namespace App\Modules\Tag;

use App\Modules\Prompt\Prompt;
use App\Modules\Service\Snowflake\HasSnowflakes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property string $name
 * @property string $icon
 * @property int    $sort_order
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 */
class Tag extends Model
{
    use SoftDeletes;
    use HasSnowflakes;
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'sort_order',
    ];

    public function prompts(): MorphToMany
    {
        return $this->morphedByMany(Prompt::class, 'taggable');
    }

    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }
}
