<?php

namespace App\Http\Controllers;

use App\Models\DemandList;
use App\Models\MonthlyDemand;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonthlyDemandsController extends Controller
{
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('Demand Monthly Quantity Updated');

        $months = [];
        $years = [];
        $currentMonth = date('n') - 2;
        $endMonth = $currentMonth + 4;
        for ($i=$currentMonth; $i<=$endMonth; $i++) {
            $months[] = date('M', mktime(0,0,0,$i, 1, date('Y')));
            $years[] = date('y', mktime(0,0,0,$i, 1, date('Y')));

        }
        $demandLists = DemandList::where('demand_id', $request->demand_id)
                                ->get();
        foreach ($demandLists as $demandList) {
            $demandListMonths = MonthlyDemand::where('demand_list_id', $demandList->id)
                ->whereIn('year', $years)
                ->pluck('month')
                ->toArray();

            foreach ($months as $key => $month) {
                if(!in_array($month, $demandListMonths)) {
                    $monthlyDemand = new MonthlyDemand();
                    $monthlyDemand->demand_list_id = $demandList->id;
                    $monthlyDemand->demand_id = $request->demand_id;
                    $monthlyDemand->month = $month;
                    $monthlyDemand->year = $years[$key];
                    $monthlyDemand->quantity = 0;
                    $monthlyDemand->save();
                }
            }
        }
        $quantities = $request->quantities;
        $monthlyDemands = MonthlyDemand::where('demand_id', $request->demand_id)
            ->whereIn('month', $months)
            ->whereIn('year', $years);

        $monthlyDemandIds = $monthlyDemands->pluck('id');
        foreach ($monthlyDemandIds as $key => $monthlyDemandId) {
           $monthlyDemand = MonthlyDemand::findOrFail($monthlyDemandId);
           $monthlyDemand->quantity = !empty($quantities[$key]) ? $quantities[$key] : 0;
           $monthlyDemand->save();
        }
       return response(true);

    }
}
