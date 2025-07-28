<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WOBOE;
use App\Models\Salesperson; // Assuming Salesperson is a model
use Illuminate\Support\Facades\Mail;
use App\Mail\WOBOEStatusMail;
use Carbon\Carbon;

class SendWOBOEStatusEmail extends Command
{
    protected $signature = 'send:wo_boe_status';
    protected $description = 'Send status emails for WOBOE starting 25 days after declaration date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today();

        // Get all `WOBOE` records where the 25th day after the `declaration_date` is today or earlier
        $boes = WOBOE::where('declaration_date', '<=', $today->subDays(24))
            ->with(['vehicles', 'workOrder.salesPerson'])  // Load vehicles and related salesperson through work order
            ->get();

        // Filter out vehicles with 'Delivered' status in PHP (as it's an appended attribute)
        $filteredBoes = $boes->map(function ($boe) {
            $boe->vehicles = $boe->vehicles->filter(function ($vehicle) {
                return $vehicle->delivery_status !== 'Delivered';  // Only non-delivered vehicles
            });
            return $boe;
        })->filter(function ($boe) {
            return $boe->vehicles->isNotEmpty();  // Keep only if there are valid vehicles
        });
        // Fetch team emails from the .env file
        $salesSupportEmail = env('SALESUPPORT_TEAM_EMAIL');
        $logisticsTeamEmail = env('LOGISTICS_TEAM_EMAIL');
        $wareHouseTeamEmail = env('WAREHOUSE_TEAM_EMAIL');
        $dontSendEmail = env('DONT_SEND_EMAIL');
    
        // Send email notifications to each salesperson
        foreach ($filteredBoes as $boe) {
            $salesperson = $boe->workOrder->salesPerson;
            $salespersonEmail = $salesperson->email ?? null; // Ensure email exists
    
            // Determine recipient list based on work order type
            if ($boe->workOrder->type === 'export_cnf') {
                // Send email only to the logistics team for export_cnf type
                $recipients = [$logisticsTeamEmail];
            } else {
                // Send email to team emails, and only include the salesperson if not in DONT_SEND_EMAIL
                $recipients = [
                    $salesSupportEmail,
                    $logisticsTeamEmail,
                    $wareHouseTeamEmail
                ];
    
                if ($salespersonEmail && $salespersonEmail !== $dontSendEmail) {
                    $recipients[] = $salespersonEmail;
                }
            }
            // Send email to the determined recipient list
            try {
                Mail::to($recipients)->send(new WOBOEStatusMail($boe, $salesperson));
            } catch (\Exception $e) {
                \Log::error($e);
            }
        }

        $this->info('WOBOE status emails have been sent successfully.');
    }
}
