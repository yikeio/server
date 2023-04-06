<?php

namespace App\Modules\Quota\Commands;

use App\Modules\Quota\Quota;
use Illuminate\Console\Command;

class CheckQuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yike:check-quota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检查配额是否过期';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Quota::query()
            ->where('expired_at', '<', now())
            ->update(['is_available' => false]);
    }
}
