<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MonthlyDemand;
use Illuminate\Http\Request;

class MonthlyDemandsController extends Controller
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
