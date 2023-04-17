<?php

namespace App\Modules\Chat\Events;

use App\Modules\Chat\Completion;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompletionCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    protected array $usage;

    public function __construct(protected Completion $completion)
    {
    }

    public function getCompletion(): Completion
    {
        return $this->completion;
    }
}
