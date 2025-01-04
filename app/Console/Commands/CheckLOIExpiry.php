<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LetterOfIndent;
use App\Models\LOIExpiryCondition;
use Carbon\Carbon;
use App\Http\Controllers\UserActivityController;

class CheckLOIExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loi_expiry:check';

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
        $letterOfIndents = LetterOfIndent::select('id','is_expired','client_id','date')
                            ->where('is_expired', false)->get();
        foreach($letterOfIndents as $letterOfIndent) {
        
            $LOItype = $letterOfIndent->client->customertype;
          
            $LOIExpiryCondition = LOIExpiryCondition::where('category_name', $LOItype)->first();
            
            if($LOIExpiryCondition) {        
                $currentDate = Carbon::now();
                $duration = $LOIExpiryCondition->expiry_duration;

                $expiryDurationType = $LOIExpiryCondition->expiry_duration_type;
                if($expiryDurationType == LOIExpiryCondition::LOI_DURATION_TYPE_YEAR) {
                    $expiryDate = Carbon::parse($letterOfIndent->date)->addYears($duration);
                }else{
                    $expiryDate = Carbon::parse($letterOfIndent->date)->addMonthsNoOverflow($duration);
                }
              
                // do not make status expired, becasue to know at which status stage it got expired
                if($currentDate->gt($expiryDate) == true) {
                    $letterOfIndent->is_expired = true;     
                    $letterOfIndent->expired_date = Carbon::now()->format('Y-m-d');
                    $letterOfIndent->timestamps = false;  
                    $letterOfIndent->save();  
                    (new UserActivityController)->createActivity('LOI '.$letterOfIndent->id.' Expired');
                }
            }
        }
        
    }
}
