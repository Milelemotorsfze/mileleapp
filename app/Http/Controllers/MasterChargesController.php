<?php

namespace App\Http\Controllers;
use App\Models\Masters\MasterCharges;

use Illuminate\Http\Request;

class MasterChargesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $masterCharges = MasterCharges::orderBy('id','DESC')->get();
        (new UserActivityController)->createActivity('Open Master Charges List');
        return view('master-charges.index', compact('masterCharges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master-charges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' =>'required',
            'addon_code' => 'required',
            'name' => 'required|unique:master_charges,name'
        ]);
        $isExist = MasterCharges::where('addon_code', $request->addon_code)
        ->where('type', $request->type)->first();

        if($isExist) {
            return  redirect()->back()->withErrors('The data with same type and addon code is already existing');
        }

        $masterCharge = new MasterCharges();
        $masterCharge->name = $request->name;
        $masterCharge->addon_code = $request->addon_code;
        $masterCharge->type = $request->type;
        $masterCharge->save();

        (new UserActivityController)->createActivity('created Master Charges');
        return redirect()->route('master-charges.index')->with(['success' => 'Matser charges created successfully']);
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
