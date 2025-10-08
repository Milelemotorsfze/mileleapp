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
        $schedule->command('reservations:clear-expired')->dailyAt('08:00');
        $schedule->command('email:send-daily-activity')->dailyAt('18:00');
        $schedule->command('leads:reassign')->hourly()->appendOutputTo(storage_path('logs/leads_reassign.log'));
        $schedule->command('notifications:send')->everyMinute();
        // $schedule->command('notificationspendingleads:send')->everyMinute();
        $schedule->command('notificationspendingsignquotation:send')->everyMinute();
        $schedule->command('notificationspendingpeospecting:send')->everyMinute();
        $schedule->command('loi_expiry:check')->daily();
        $schedule->command('vehicles:send-estimation-reminders')->dailyAt('09:00');
        // $schedule->command('send:wo_boe_status')->dailyAt('09:00');
        // $schedule->command('claim_submission_reminder')->dailyAt('09:00');
        // $schedule->command('backup:run --only-db')->daily();
        
        // Leads reminder emails at 9AM and 6PM (New leads)
        $schedule->command('leads:send-reminder')->dailyAt('09:00');
        $schedule->command('leads:send-reminder')->dailyAt('18:00');
        
        // Friday reminder emails at 9AM (Contacted/Working leads)
        $schedule->command('leads:send-friday-reminder')->weeklyOn(5, '09:00'); // Friday at 9AM
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
        // \App\Console\Commands\SendNotificationspendingleads::class,
        \App\Console\Commands\SendNotificationspendingsignquotation::class,
        \App\Console\Commands\SendNotificationspendingpeospecting::class,
        \App\Console\Commands\CheckLOIExpiry::class,
        \App\Console\Commands\ClearExpiredReservations::class,
        \App\Console\Commands\SendEstimationDateReminders::class,
        \App\Console\Commands\SendLeadsReminder::class,
        \App\Console\Commands\SendFridayLeadsReminder::class,
    ];
}
