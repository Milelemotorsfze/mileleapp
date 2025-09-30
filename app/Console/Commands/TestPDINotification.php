<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicles;
use App\Mail\PDICompletionNotification;
use Illuminate\Support\Facades\Mail;

class TestPDINotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pdi-notification {vehicle_id} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PDI completion notification by sending email to specified address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vehicleId = $this->argument('vehicle_id');
        $testEmail = $this->argument('email');
        
        $vehicle = Vehicles::with(['so.salesperson', 'variant.brand', 'exterior', 'interior', 'warehouse'])
            ->find($vehicleId);
            
        if (!$vehicle) {
            $this->error("Vehicle with ID {$vehicleId} not found.");
            return 1;
        }
        
        // Use real sales person data from database, or create a test one if not found
        $salesPerson = null;
        if ($vehicle->so && $vehicle->so->salesperson) {
            $salesPerson = $vehicle->so->salesperson;
            // Override email for testing
            $salesPerson->email = $testEmail;
        } else {
            // Create a mock sales person for testing if no real one exists
            $salesPerson = (object) [
                'name' => 'Test Sales Person',
                'email' => $testEmail
            ];
        }
        
        $pdiDate = now();
        
        try {
            Mail::to($testEmail)->send(new PDICompletionNotification($vehicle, $salesPerson, $pdiDate));
            $this->info("PDI completion notification sent successfully to {$testEmail}");
            $this->info("Vehicle: {$vehicle->vin} | Brand: " . ($vehicle->variant->brand->brand_name ?? 'N/A'));
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to send notification: " . $e->getMessage());
            return 1;
        }
    }
}
