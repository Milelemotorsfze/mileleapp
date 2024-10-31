<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LetterOfIndent;
use App\Models\LOIExpiryCondition;
use Carbon\Carbon;

class CheckLOIExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the LOI Expiry';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $letterOfIndents = LetterOfIndent::select('id','is_expired','client_id','date')->get();
        foreach($letterOfIndents as $letterOfIndent) {
            $LOItype = $letterOfIndent->client->customertype;
            $LOIExpiryCondition = LOIExpiryCondition::where('category_name', $LOItype)->first();
            if($LOIExpiryCondition && $letterOfIndent->is_expired == false) {        
                $currentDate = Carbon::now();
                $year = $LOIExpiryCondition->expiry_duration_year;
                $expiryDate = Carbon::parse($letterOfIndent->date)->addYears($year);
                // do not make status expired, becasue to know at which status stage it got expired
                if($currentDate->gt($expiryDate) == true) {
                    $letterOfIndent->is_expired = true;     
                    $letterOfIndent->expired_date = Carbon::now()->format('Y-m-d');
                    $letterOfIndent->timestamps = false;  
                    $letterOfIndent->save();  
                    (new UserActivityController)->createActivity('LOI '.$letterOfIndent->id.' Expired');
                    // info($letterOfIndent);
                    info("expiry shecduler");

                }
                // else{
                //     $letterOfIndent->is_expired = false;  
                //     $letterOfIndent->expired_date = NULL;  
                //     $letterOfIndent->timestamps = false;               
                //     $letterOfIndent->save();  
                //     // info($letterOfIndent);
                //     info("else expiry shecduler");
                // }
            }

        }
        
    }
}
