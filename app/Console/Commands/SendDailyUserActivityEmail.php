<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserActivityEmail;
use App\Models\UserActivities;

class SendDailyUserActivityEmail extends Command
{
    protected $signature = 'email:send-daily-activity';
    protected $description = 'Send daily user activity email';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $activities = UserActivities::with('user')
                ->whereDate('created_at', now()->toDateString())
                ->get();
    
            // Log the retrieved activities in user_activity.log
            Log::channel('user_activity')->info('Daily User Activities Retrieved', [
                'activities' => $activities->toArray(),
            ]);
    
            // Send the email with the activities
            Mail::to('rejitha.rajendran@milele.com')->send(new UserActivityEmail($activities));
            Log::channel('user_activity')->info('Email sent successfully to rejitha.rajendran@milele.com');
    
        } catch (\Exception $e) {
            // Log any error that occurs in user_activity.log
            Log::channel('user_activity')->error('Error sending daily user activity email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
        // $activities = UserActivities::with('user')
        //     ->whereDate('created_at', now()->toDateString())
        //     ->get();
        //     $this->info($activities);
        // // Mail::to('abdul@milele.com')->send(new UserActivityEmail($activities));
        // Mail::to('rejitha.rajendran@milele.com')->send(new UserActivityEmail($activities));
    }
}
