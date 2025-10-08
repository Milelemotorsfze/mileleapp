<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CallsReminderController;
use Illuminate\Support\Facades\Log;

class SendFridayLeadsReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:send-friday-reminder {--sales-person= : Send reminder to specific sales person ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Friday reminder emails to sales persons with their contacted and working leads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Friday leads follow-up process...');
        
        try {
            $reminderController = new CallsReminderController();
            
            // Check if specific sales person ID is provided
            $salesPersonId = $this->option('sales-person');
            
            if ($salesPersonId) {
                $this->info("Sending Friday reminder to specific sales person ID: {$salesPersonId}");
                $result = $reminderController->sendReminderToSalesPerson($salesPersonId, 'contacted_working');
            } else {
                $this->info("Sending Friday reminders to all sales persons with contacted/working leads");
                $result = $reminderController->sendReminderEmails('contacted_working');
            }
            
            $response = $result->getData();
            
            if ($response->success) {
                $this->info("✅ Friday reminder emails sent successfully!");
                $this->info("📧 Emails sent: " . ($response->emails_sent ?? 'N/A'));
                $this->info("📊 Total leads: " . ($response->total_leads ?? $response->leads_count ?? 'N/A'));
                
                Log::info("Friday leads reminder command executed successfully", [
                    'emails_sent' => $response->emails_sent ?? null,
                    'total_leads' => $response->total_leads ?? $response->leads_count ?? null
                ]);
            } else {
                $this->error("❌ Error sending Friday reminder emails: " . $response->message);
                Log::error("Friday leads reminder command failed: " . $response->message);
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Exception occurred: " . $e->getMessage());
            Log::error("Friday leads reminder command exception: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
