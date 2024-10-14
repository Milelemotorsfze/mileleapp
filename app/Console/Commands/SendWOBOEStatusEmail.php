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

        // Get all records where the 25th day after the declaration date is today or earlier
        $boes = WOBOE::where('declaration_date', '<=', $today->subDays(25))->get();

        foreach ($boes as $boe) {
            // Assuming each BOE is associated with a salesperson, fetch the salesperson
            $salesperson = Salesperson::where('wo_id', $boe->wo_id)->first(); // Example relationship
            
            // Send the email to the salesperson or default to a specific email
            Mail::to('rejitha.rajendran@milele.com')//$salesperson->email ?? 
                ->send(new WOBOEStatusMail($boe, $salesperson));
        }

        $this->info('WOBOE status emails have been sent successfully.');
    }
}
