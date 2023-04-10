<?php

namespace App\Console;

use App\Modules\Payment\Command\CheckPayment;
use App\Modules\Quota\Commands\CheckQuota;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CheckQuota::class,
        CheckPayment::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->command(CheckQuota::class)->everyMinute()->withoutOverlapping();
        $schedule->command(CheckPayment::class)->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
