<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\WOVehicles;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendClaimSubmissionReminderEmail;

class SendClaimSubmissionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'claim_submission_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'claim submission reminder';

    /**
     * Execute the console command.
     */
    
    public function handle()
    {
        $today = Carbon::today();
        $reminderDetails = [
            44 => ['label' => 'Reminder 1', 'remainingDays' => 14],
            49 => ['label' => 'Reminder 2', 'remainingDays' => 9],
            54 => ['label' => 'Reminder 3', 'remainingDays' => 4],
            56 => ['label' => 'Reminder 4', 'remainingDays' => 3],
            57 => ['label' => 'Reminder 5', 'remainingDays' => 2],
            58 => ['label' => 'Reminder 6', 'remainingDays' => 1],
        ];
        
        // Loop through each vehicle
        $vehicles = WOVehicles::select('id', 'vin', 'work_order_id')
            ->whereDoesntHave('claim')
            ->get();
        
        foreach ($vehicles as $vehicle) {
            // Check if vehicle meets the required conditions
            if ($vehicle->delivery_status !== 'Delivered' 
                && $vehicle->workOrder->sales_support_data_confirmation === 'Confirmed'
                && $vehicle->workOrder->finance_approval_status === 'Approved' 
                && $vehicle->workOrder->coo_approval_status === 'Approved'
                && $vehicle->woBoe) {
        
                // Calculate the difference in days between today and the declaration date
                $declarationDate = Carbon::parse($vehicle->woBoe->declaration_date);
                $daysAgo = $declarationDate->diffInDays($today);
        
                // Check if there is a reminder for the calculated daysAgo
                if (isset($reminderDetails[$daysAgo])) {
                    $details = $reminderDetails[$daysAgo];
        
                    // Access the salesperson related to the work order
                    $salesperson = $vehicle->workOrder->salesPerson;
                    $salesSupportEmail = env('SALESUPPORT_TEAM_EMAIL');
                    $logisticsTeamEmail = env('LOGISTICS_TEAM_EMAIL');
                    $developerEmail = env('DEVELOPER_EMAIL');
        
                    // Define the email content with the reminder label and remaining days
                    $emailContent = "
                        Dear {$salesperson->name},
        
                        Kindly ask the customer to submit the exit documents for claim submission. {$details['remainingDays']} days remaining to submit the claim without the fine.
        
                        Please note that if the customer has not yet exported the vehicle, kindly ask them to bring the vehicle back to DUCAMZ. We will cancel and then reprocess these documents, and the customer will be charged 1560 AED for this.
                    ";
                    
                    // Send email to salesperson, sales support, logistics team, and developer
                    $recipients = [$salesperson->email, $salesSupportEmail, $logisticsTeamEmail];
                 
                    // Send email with dynamic subject containing the label
                    Mail::to($recipients)
                        ->send(new SendClaimSubmissionReminderEmail($vehicle, $emailContent, $details['label']));
                }
            }
        }
    }
}
