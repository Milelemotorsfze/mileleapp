<?php

namespace App\Http\Controllers;

use App\Models\DemandList;
use App\Models\MasterModel;
use App\Models\MonthlyDemand;
use App\Models\Varaint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class DemandListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'model' => 'required',
            'sfx' => 'required',
            'variant' => 'required',
        ]);

        DB::beginTransaction();
        $demadList = new DemandList();
        $demadList->demand_id = $request->demand_id;
        $variant = Varaint::find($request->variant);

        if($variant) {
            $masterModel = MasterModel::where('sfx', $request->sfx)
                ->where('model', $request->model)
                ->where('variant_id', $variant->id)->first();
            $demadList->master_model_id = $masterModel->id ?? '';
        }

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

        return redirect()->route('demands.edit',$request->demand_id)->with('message', 'Demand Item added successfully' );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try
        {
            $demandList = DemandList::find($id);
            $monthlyDemands = MonthlyDemand::where('demand_list_id', $demandList->id)->get();
            foreach ($monthlyDemands as $monthlyDemand) {
                $monthlyDemand->delete();
            }
            $demandList->delete();
            return response(true);
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }


    }
}
