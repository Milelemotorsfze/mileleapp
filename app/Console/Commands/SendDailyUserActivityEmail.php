<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        $activities = UserActivities::with('user')
            ->whereDate('created_at', now()->toDateString())
            ->get();
        Mail::to('abdul@milele.com')->send(new UserActivityEmail($activities));
    }
}
