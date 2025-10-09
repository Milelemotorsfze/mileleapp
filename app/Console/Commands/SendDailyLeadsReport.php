<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CallsReminderController;
use Illuminate\Support\Facades\Log;

class SendDailyLeadsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leads:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily leads report to management with structured table data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily leads report process...');
        
        try {
            $reminderController = new CallsReminderController();
            $result = $reminderController->sendDailyReport();
            
            $response = $result->getData();
            
            if ($response->success) {
                $this->info("✅ Daily report sent successfully!");
                $this->info("📧 Email sent to: basharat.ali@milele.com");
                $this->info("📊 Total leads: " . ($response->total_leads ?? 'N/A'));
                $this->info("👥 Sales persons: " . ($response->sales_persons ?? 'N/A'));
                
                Log::info("Daily leads report command executed successfully", [
                    'emails_sent' => $response->emails_sent ?? null,
                    'total_leads' => $response->total_leads ?? null,
                    'sales_persons' => $response->sales_persons ?? null
                ]);
            } else {
                $this->error("❌ Error sending daily report: " . $response->message);
                Log::error("Daily leads report command failed: " . $response->message);
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Exception occurred: " . $e->getMessage());
            Log::error("Daily leads report command exception: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
