<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Fellowup;
use App\Models\LeadsNotifications;

class SendNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send notifications for follow-ups';

    public function handle()
    {
        $followUps = Fellowup::whereDate('date', '=', now()->toDateString())
                    ->whereTime('time', '>=', now()->subMinutes(2)->toTimeString())
                    ->whereTime('time', '<=', now()->addMinutes(2)->toTimeString())        
                    ->get();
        foreach ($followUps as $followUp) {
            $existingNotification = LeadsNotifications::where('calls_id', $followUp->calls_id)
            ->where('user_id', $followUp->created_by)
            ->where('status', 'New')
            ->where('category', 'Fellow Up')
            ->exists();
        if (!$existingNotification) {
        $LeadsNotifications = New LeadsNotifications ();
        $time = \Carbon\Carbon::createFromFormat('H:i:s', $followUp->time)->format('h:i A');
        $date = \Carbon\Carbon::parse($followUp->date)->format('d F Y');
        $LeadsNotifications->remarks = $followUp->sales_notes . ' - ' . $followUp->method . ' ( Date: ' . $date . ' Time: ' . $time . ')';
        $LeadsNotifications->created_at = $followUp->date;
        $LeadsNotifications->status = "New";
        $LeadsNotifications->category = "Fellow Up";
        $LeadsNotifications->calls_id = $followUp->calls_id;
        $LeadsNotifications->user_id = $followUp->created_by;
        $LeadsNotifications->save();
            }
        }
    }
}