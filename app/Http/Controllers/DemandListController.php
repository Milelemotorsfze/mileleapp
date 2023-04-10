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
        info("test");
        info($request->all());

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
        //
    }
}
