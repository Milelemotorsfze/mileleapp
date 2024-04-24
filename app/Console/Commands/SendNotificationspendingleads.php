<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Calls;
use App\Models\LeadsNotifications;

class SendNotificationspendingleads extends Command
{
    protected $signature = 'notificationspending:send';
    protected $description = 'Send notifications for Pending Leads';

    public function handle()
    {
        $calls = Calls::whereDate('created_at', '=', now()->subDays(1)->toDateString())
                        ->where('status', 'New')
                        ->get();
        foreach ($calls as $call) {
            $existingNotification = LeadsNotifications::where('calls_id', $call->calls_id)
            ->where('user_id', $call->sales_person)
            ->where('status', 'New')
            ->where('category', 'Pending Lead')
            ->exists();
        if (!$existingNotification) {
        $LeadsNotifications = New LeadsNotifications ();
        $LeadsNotifications->remarks = $call->name . ' Phone ' . $call->phone . ' Remarks: ' . $call->remarks;
        $LeadsNotifications->created_at = now();
        $LeadsNotifications->status = "New";
        $LeadsNotifications->category = "Pending Lead";
        $LeadsNotifications->calls_id = $call->id;
        $LeadsNotifications->user_id = $call->sales_person;
        $LeadsNotifications->save();
            }
        }
    }
}