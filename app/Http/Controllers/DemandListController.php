<?php

namespace App\Http\Controllers;

use App\Models\DemandList;
use App\Models\MonthlyDemand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DemandListController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'model' => 'required',
            'sfx' => 'required',
            'variant_name' => 'required',
        ]);

        DB::beginTransaction();
        $demadList = new DemandList();
        $demadList->demand_id = $request->demand_id;
        $demadList->model = $request->model;
        $demadList->sfx = $request->sfx;
        $demadList->variant_name = $request->variant_name;
        $demadList->created_by = Auth::id();
        $demadList->save();

        foreach ($request->quantity as $key => $qty) {
            $monthlyDemand = new MonthlyDemand();
            $monthlyDemand->demand_list_id = $demadList->id;
            $monthlyDemand->demand_id = $request->demand_id;
            $monthlyDemand->month = Carbon::parse($request->month[$key])->format('M');
            $monthlyDemand->year = Carbon::parse($request->month[$key])->format('y');
            $monthlyDemand->quantity = $qty;
            $monthlyDemand->save();
        }

        DB::commit();

        return response($demadList, 200);

    }
}
