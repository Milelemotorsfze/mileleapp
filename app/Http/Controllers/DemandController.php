<?php

namespace App\Http\Controllers;

use App\Models\DemandList;
use App\Models\MasterModel;
use App\Models\MonthlyDemand;
use App\Models\Varaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Demand;

class DemandController extends Controller
{
    public function index()
    {

    }
    public function create()
    {
        return view('demands.create');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier' => 'required',
            'whole_saler' => 'required',
            'steering' => 'required'
        ]);

        $demand = Demand::where('supplier',$request->supplier)
            ->where('whole_saler', $request->whole_saler)
            ->where('steering', $request->steering)
            ->first();

        if (!$demand) {
            $demand = new Demand();
            $demand->supplier = $request->input('supplier');
            $demand->whole_saler = $request->input('whole_saler');
            $demand->steering = $request->input('steering');
            $demand->created_by = Auth::id();
            $demand->save();
        }

        return redirect()->route('demands.edit',['demand' => $demand->id])->with('message','Demand created successfully');
    }
    public function edit(string $id)
    {
        $cu = date('n') - 2;
        $ru   = $cu + 4;
        $totalYearlyQuantities = [];
        for($k=$cu;$k<=$ru;$k++) {
            $month = date('M', mktime(0, 0, 0, $k, 10));
            $year = date('y', mktime(0, 0, 0, $k, 10));
            $data = MonthlyDemand::where('demand_id',$id)
                ->where('month',$month)
                ->where('year',$year)
                ->sum('quantity');

            $totalYearlyQuantities[] = $data;
        }

        $demand = Demand::findOrFail($id);
        $demandLists = DemandList::where('demand_id',$id)->get();

        $months = [];
        $years = [];
        $currentMonths = [];
        $currentMonth = date('n') - 2;
        $endMonth = $currentMonth + 4;
        for ($i=$currentMonth; $i<=$endMonth; $i++) {
            $months[] = date('M y', mktime(0,0,0,$i, 1, date('Y')));
            $years[] = date('y', mktime(0,0,0,$i, 1, date('Y')));
            $currentMonths[] = date('M', mktime(0,0,0,$i, 1, date('Y')));
        }

        $monthlyDemands = MonthlyDemand::where('demand_id',$id)
            ->whereIn('month', $currentMonths)
            ->whereIn('year', $years)
            ->get();
//        return $monthlyDemands;

        $models = MasterModel::all();
        return view('demands.edit',
            compact('demand','demandLists','models','months','monthlyDemands','totalYearlyQuantities'));
    }
    public function getSFX(Request $request)
    {
        $data = MasterModel::where('model', $request->model)
            ->pluck('sfx');
        return $data;
    }
    public function getVariant(Request $request)
    {
        $data = Varaint::with('masterModel')
            ->whereHas('masterModel', function ($query) use($request) {
                $query->where('sfx', $request->sfx);
            })
            ->pluck('name');
        return $data;
    }
}
