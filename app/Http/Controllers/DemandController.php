<?php

namespace App\Http\Controllers;

use App\Models\MasterModel;
use App\Models\Varaint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Demand;

class DemandController extends Controller
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
        $months = [];
        $currentMonth = Carbon::now()->format('m');
        $endMonth = $currentMonth + 4;
        for ($i=$currentMonth; $i<=$endMonth; $i++) {
            $months[] = date('M y', mktime(0,0,0,$i, 1, date('Y')));
        }

        $models = MasterModel::all();
        return view('demands.create',compact('models','months'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier' => 'required',
            'whole_saler' => 'required',
            'steering' => 'required'
        ]);

        $demand = new Demand();
        $demand->supplier = $request->input('supplier');
        $demand->whole_saler = $request->input('whole_saler');
        $demand->steering = $request->input('steering');
        $demand->created_by = Auth::id();
        $demand->save();

        return response($demand, 200);
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
        $demands = Demand::where('id', $id)->get();
        return view('demands.edit', compact('demands'));
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
    public function getSFX(Request $request)
    {
        $data = MasterModel::where('model', $request->model)
            ->pluck('sfx');
        return $data;
    }
    public function getVariant(Request $request)
    {
        $data = Varaint::where('sfx', $request->sfx)
            ->pluck('name');
        return $data;
    }
}
