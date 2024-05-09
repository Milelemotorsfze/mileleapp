<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Calls;
use Illuminate\Support\Facades\DB;
use App\Models\SalesPersonLaugauges;
use Carbon\Carbon;
class ReassignLeads extends Command
{
    protected $signature = 'leads:reassign';
    protected $description = 'Reassign leads that are older than 24 hours and still new';

    public function handle()
    {
        $leads = Calls::where('status', 'new')
                     ->where('created_at', '<', Carbon::now()->subDay())
                     ->get();
        foreach ($leads as $lead) {
            $newSalesPersonId = $this->getNewSalesPersonId($lead->id);
            $lead->sales_person = $newSalesPersonId;
            $lead->assign_time = Carbon::now();
            $lead->save();

            $this->info("Lead {$lead->id} reassigned to sales person {$lead->sales_person}");
        }
    }
    protected function getNewSalesPersonId($leadId)
    {
        $call = Calls::find($leadId);
        $salesPersons = SalesPersonLaugauges::select('sales_person_laugauges.sales_person', DB::raw('COUNT(calls.id) as call_count'))
        ->leftJoin('calls', function ($join) use ($call) {
            $join->on('sales_person_laugauges.sales_person', '=', 'calls.sales_person')
                 ->where('calls.status', '=', 'New');
        })
        ->where('sales_person_laugauges.language', $call->language)
        ->where('sales_person_laugauges.sales_person', '!=', $call->sales_person)
        ->groupBy('sales_person_laugauges.sales_person')
        ->orderBy('call_count', 'asc')
        ->first();
        if(!$salesPersons)
        {
            $salesPersons = SalesPersonLanguages::select('sales_person_languages.sales_person', DB::raw('COUNT(calls.id) as call_count'))
            ->leftJoin('calls', function ($join) use ($call) {
                $join->on('sales_person_languages.sales_person', '=', 'calls.sales_person')
                     ->where('calls.status', '=', 'New');
            })
            ->where('sales_person_languages.sales_person', '!=', $call->sales_person)
            ->groupBy('sales_person_languages.sales_person')
            ->orderBy('call_count', 'asc')
            ->first();  
        }
        return $salesPersons->sales_person;
    }
}
