<?php

namespace App\Http\Controllers;

use App\Models\DemandList;
use App\Models\MonthlyDemand;
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
        return $request->all();

        info("test");
        info($request->all());

        $this->validate($request, [
            'model' => 'required',
            'sfx' => 'required',
            'variant_name' => 'required',
//            'demand_id' => 'required'
        ]);

        DB::beginTransaction();
        $demadList = new DemandList();
        $demadList->demand_id = $request->demand_id;
        $demadList->model = $request->model;
        $demadList->sfx = $request->sfx;
        $demadList->variant_name = $request->variant_name;
        $demadList->created_by = Auth::id();
        $demadList->save();

//        $monthlyDemand = new MonthlyDemand();
//        foreach ($quantity as key =>
//        $quantity)



        DB::commit();





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
