<?php

namespace App\Http\Controllers;

use App\Models\MonthlyDemand;
use Illuminate\Http\Request;

class MonthlyDemandsController extends Controller
{
    public function store(Request $request)
    {
       $monthlyDemandIds = MonthlyDemand::where('demand_id', $request->demand_id)
           ->pluck('id')->toArray();

       $quantities = $request->quantities;
       foreach ($monthlyDemandIds as $key => $monthlyDemandId) {
           $monthlyDemand = MonthlyDemand::findOrFail($monthlyDemandId);
           $monthlyDemand->quantity = $quantities[$key];
           $monthlyDemand->save();
       }
       return response(true);

    }
}
