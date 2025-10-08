<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CallsReminderController;
use Illuminate\Support\Facades\Log;

class SendLeadsReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:send-reminder {--sales-person= : Send reminder to specific sales person ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to sales persons with their assigned new leads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting leads reminder process...');
        
        try {
            $reminderController = new CallsReminderController();
            
            // Check if specific sales person ID is provided
            $salesPersonId = $this->option('sales-person');
            
            if ($salesPersonId) {
                $this->info("Sending reminder to specific sales person ID: {$salesPersonId}");
                $result = $reminderController->sendReminderToSalesPerson($salesPersonId);
            } else {
                $this->info("Sending reminders to all sales persons with new leads");
                $result = $reminderController->sendReminderEmails();
            }
            
            $response = $result->getData();
            
            if ($response->success) {
                $this->info("✅ Reminder emails sent successfully!");
                $this->info("📧 Emails sent: " . ($response->emails_sent ?? 'N/A'));
                $this->info("📊 Total leads: " . ($response->total_leads ?? $response->leads_count ?? 'N/A'));
                
                Log::info("Leads reminder command executed successfully", [
                    'emails_sent' => $response->emails_sent ?? null,
                    'total_leads' => $response->total_leads ?? $response->leads_count ?? null
                ]);
            } else {
                $this->error("❌ Error sending reminder emails: " . $response->message);
                Log::error("Leads reminder command failed: " . $response->message);
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Exception occurred: " . $e->getMessage());
            Log::error("Leads reminder command exception: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
