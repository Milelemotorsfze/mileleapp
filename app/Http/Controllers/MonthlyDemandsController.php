<?php

namespace App\Http\Controllers;

use App\Models\MonthlyDemand;
use Illuminate\Http\Request;

class MonthlyDemandsController extends Controller
{
    public function store(Request $request)
    {
        $months = [];
        $years = [];
        $currentMonth = date('n') - 2;
        $endMonth = $currentMonth + 4;
        for ($i=$currentMonth; $i<=$endMonth; $i++) {
            $months[] = date('M', mktime(0,0,0,$i, 1, date('Y')));
            $years[] = date('y', mktime(0,0,0,$i, 1, date('Y')));

        }
       $monthlyDemandIds = MonthlyDemand::where('demand_id', $request->demand_id)
           ->whereIn('month', $months)
           ->whereIn('year', $years)
           ->pluck('id')
           ->toArray();

       $quantities = $request->quantities;

       foreach ($monthlyDemandIds as $key => $monthlyDemandId) {
           $monthlyDemand = MonthlyDemand::findOrFail($monthlyDemandId);
           $monthlyDemand->quantity = !empty($quantities[$key]) ? $quantities[$key] : 0;
           $monthlyDemand->save();
       }
       return response(true);

    }
}
