<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quotation;
use App\Models\Calls;
use App\Models\LeadsNotifications;

class SendNotificationspendingsignquotation extends Command
{
    protected $signature = 'notificationspendingsignquotation:send';
    protected $description = 'Send notifications for Pending Quotation Signed';

    public function handle()
    {
        $quotations = Quotation::whereDate('date', '=', now()->subDays(1)->toDateString())
                        ->whereNull('signature_status')
                        ->get();
        foreach ($quotations as $quotation) {
            $existingNotification = LeadsNotifications::where('calls_id', $quotation->calls_id)
            ->where('user_id', $quotation->created_by)
            ->where('status', 'New')
            ->where('category', 'Quotation Fellow Up')
            ->exists();
        if (!$existingNotification) {
        $call = Calls::find($quotation->calls_id);
        $LeadsNotifications = New LeadsNotifications ();
        $LeadsNotifications->remarks = $call->name . ' Phone ' . $call->phone . ' Remarks: ' . $call->remarks;
        $LeadsNotifications->created_at = now();
        $LeadsNotifications->status = "New";
        $LeadsNotifications->category = "Quotation Fellow Up";
        $LeadsNotifications->calls_id = $quotation->calls_id;
        $LeadsNotifications->user_id = $quotation->created_by;
        $LeadsNotifications->save();
            }
        }
    }
}