<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\VehiclesController;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendEstimationDateReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicles:send-estimation-reminders {--force : Force send even if no vehicles found} {--test : Test mode - show what would be sent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send estimation date reminders for vehicles with upcoming estimation dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting estimation date reminder process...');
        
        try {
            $today = Carbon::now();
            
            // Check for vehicles with estimation dates in the next 5 days
            $vehiclesNeedingReminders = collect();
            
            for ($days = 1; $days <= 5; $days++) {
                $targetDate = $today->copy()->addDays($days)->format('Y-m-d');
                
                $vehicles = \App\Models\Vehicles::where('estimation_date', $targetDate)
                    ->whereNotNull('estimation_date')
                    ->where('status', '!=', 'cancel')
                    ->with(['variant.brand', 'variant.master_model_lines', 'warehouse', 'purchasingOrder'])
                    ->get();
                
                if ($vehicles->count() > 0) {
                    // Add days remaining info to each vehicle
                    foreach ($vehicles as $vehicle) {
                        $vehicle->days_remaining = $days;
                    }
                    
                    $vehiclesNeedingReminders = $vehiclesNeedingReminders->merge($vehicles);
                }
            }
            
            // Send email if vehicles found
            if ($vehiclesNeedingReminders->count() > 0) {
                $this->sendEstimationReminderEmail($vehiclesNeedingReminders);
                $this->info("✅ Email sent for {$vehiclesNeedingReminders->count()} vehicles");
            } else {
                $this->info("ℹ️  No vehicles found requiring reminders");
                \Illuminate\Support\Facades\Log::info('No estimation reminder email sent - no vehicles found with upcoming estimation dates');
            }
            
            $this->info('✅ Estimation reminder process completed');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Send estimation reminder email
     */
    private function sendEstimationReminderEmail($vehicles)
    {
        try {
            // Get all team email addresses
            $departmentEmails = config('departments.estimation_reminders', []);

            $allEmails = [];
            foreach ($departmentEmails as $emails) {
                $allEmails = array_merge($allEmails, $emails);
            }
            
            // Remove duplicates and filter out default placeholder emails
            $allEmails = array_unique($allEmails);
            $allEmails = array_filter($allEmails, function($email) {
                return !str_contains($email, '@company.com') && !empty($email);
            });
            
            // Log email configuration
            \Illuminate\Support\Facades\Log::info('Estimation reminder email configuration', [
                'total_emails_found' => count($allEmails),
                'emails' => $allEmails
            ]);
            
            // Check if we have valid emails
            if (empty($allEmails)) {
                \Illuminate\Support\Facades\Log::warning('No valid emails found in departments config - email not sent');
                throw new \Exception('No valid email addresses configured for estimation reminders');
            }
            
            // Send single consolidated email to all teams
            \Illuminate\Support\Facades\Mail::to($allEmails)->send(new \App\Mail\EstimationDateReminder($vehicles, null));
            
            // Log the email sent
            \Illuminate\Support\Facades\Log::info('Estimation reminder email sent for ' . $vehicles->count() . ' vehicles');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send estimation reminder email', [
                'error' => $e->getMessage(),
                'vehicles_count' => $vehicles->count(),
                'timestamp' => now()->toISOString()
            ]);
            throw new \Exception("Failed to send email: " . $e->getMessage());
        }
    }
}
