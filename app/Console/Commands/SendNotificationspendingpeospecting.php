<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prospecting;
use App\Models\Calls;
use App\Models\LeadsNotifications;

class SendNotificationspendingpeospecting extends Command
{
    protected $signature = 'notificationspendingpeospecting:send';
    protected $description = 'Send notifications for Pending Prospecting';

    public function handle()
    {
        $prospectings = Prospecting::whereDate('created_at', '=', now()->subDays(1)->toDateString())
        ->whereHas('call', function ($query) {
            $query->where('status', 'Prospecting');
        })
        ->get();    
        foreach ($prospectings as $prospecting) {
            $existingNotification = LeadsNotifications::where('calls_id', $prospecting->calls_id)
            ->where('user_id', $prospecting->created_by)
            ->where('status', 'New')
            ->where('category', 'Processed Lead')
            ->exists();
        if (!$existingNotification) {
        $call = Calls::find($prospecting->calls_id);
        $LeadsNotifications = New LeadsNotifications ();
        $LeadsNotifications->remarks = $call->name . ' Phone ' . $call->phone . ' Remarks: ' . $call->remarks;
        $LeadsNotifications->created_at = now();
        $LeadsNotifications->status = "New";
        $LeadsNotifications->category = "Processed Lead";
        $LeadsNotifications->calls_id = $prospecting->calls_id;
        $LeadsNotifications->user_id = $prospecting->created_by;
        $LeadsNotifications->save();
            }
        }
    }
}