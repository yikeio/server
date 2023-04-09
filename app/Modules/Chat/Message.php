<?php

namespace App\Modules\Chat;

use App\Modules\Chat\Enums\MessageRole;
use App\Modules\Chat\Filters\MessageFilter;
use App\Modules\Service\Snowflake\HasSnowflakes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasSnowflakes;
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    public $incrementing = false;

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'raws',
        'tokens_count',
    ];

    protected $casts = [
        'id' => 'string',
        'conversation_id' => 'string',
        'role' => MessageRole::class,
        'raws' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    protected static function newFactory(): MessageFactory
    {
        return MessageFactory::new();
    }

    public function getModelFilterClass(): string
    {
        return MessageFilter::class;
    }
}
