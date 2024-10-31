<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('export:csv')->monthlyOn(date('t'), '00:00');
        $schedule->command('email:send-daily-activity')->dailyAt('18:00');
        $schedule->command('leads:reassign')->hourly()->appendOutputTo(storage_path('logs/leads_reassign.log'));
        $schedule->command('notifications:send')->everyMinute();
        $schedule->command('notificationspendingleads:send')->everyMinute();
        $schedule->command('notificationspendingsignquotation:send')->everyMinute();
        $schedule->command('notificationspendingpeospecting:send')->everyMinute();
        $schedule->command('expiry:check')->dailyAt('12.50')()->appendOutputTo(storage_path('logs/scheduler.log'));
        $schedule->command('send:wo_boe_status')->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    protected $commands = [
        \App\Console\Commands\ExportCSV::class,
        \App\Console\Commands\SendNotifications::class,
        \App\Console\Commands\SendNotificationspendingleads::class,
        \App\Console\Commands\SendNotificationspendingsignquotation::class,
        \App\Console\Commands\SendNotificationspendingpeospecting::class,
        \App\Console\Commands\CheckLOIExpiry::class,
    ];
}
