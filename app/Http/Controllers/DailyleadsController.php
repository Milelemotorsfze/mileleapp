<?php

namespace App\Http\Controllers;

use App\Models\Dailyleads;
use App\Models\Calls;
use App\Models\CallsRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyleadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $id = Auth::user()->id;
       $pendingdata = Calls::where('status', 'New')->where('sales_person', $id)->get();
       $intialcallsdata = Calls::where('status', 'Initial Contact')->where('sales_person', $id)->get();
       return view('dailyleads.index',compact('pendingdata', 'intialcallsdata'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dailyleads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dailyleads $dailyleads)
    {
        //
    }
    public function processStep(Request $request)
{
    $status = $request->input('status');
    $calls = Calls::where('status', $status)->get();

    $data = [];

    foreach ($calls as $call) {
        $modelLines = CallsRequirement::where('lead_id', $call->id)
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->pluck('master_model_lines.model_line')
            ->toArray();

        $data[] = [
            'created_at' => $call->created_at,
            'name' => $call->name,
            'type' => $call->type,
            'phone' => $call->phone,
            'email' => $call->email,
            'model_lines' => $modelLines,
            'custom_brand_model' => $call->custom_brand_model,
            'language' => $call->	language,
            'remarks' => $call->remarks
        ];
    }

    return response()->json($data);
}
public function prospecting($id)
    {
        $dailyLead = Calls::findOrFail($id);
        return view('dailyleads.prospecting', ['dailyLead' => $dailyLead]);
    }
}
