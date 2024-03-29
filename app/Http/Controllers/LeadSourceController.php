<?php

namespace App\Http\Controllers;

use App\Models\LeadSource;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivities;
use Illuminate\Http\Request;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Lead Source Information";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $data = LeadSource::orderBy('status','ASC')->get();
        return view('calls.lead_source',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open lead Source Create Page";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.lead_source_create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Lead Source";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $this->validate($request, [
            'source_name' => 'required',
        ], [
            'source_name.required' => 'Please enter your source name.', // Custom error message
        ]);     
        $sourceName = $request->input('source_name');
        $existingRecord = LeadSource::where('source_name', $sourceName)->first();
        if ($existingRecord) {
            return redirect()->back()
                ->with('error', 'Record already exists');
        }
        $data = [
            'source_name' => $sourceName,
            'created_by' => Auth::id(),
            'status' => "active",
        ];
        // dd($data);
        $model = new LeadSource($data);
        $model->save();
        return redirect()->route('lead_source.index')
            ->with('success', 'Record created successfully');
    }    
    /**
     * Display the specified resource.
     */
    public function show(LeadSource $leadSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Edit Page Open Lead Source";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $record = LeadSource::findOrFail($id);
    return view('calls.lead_source_edit', ['record' => $record]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Edit the Lead Source";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $record = LeadSource::findOrFail($id);
        $source_name = $request->input('source_name');
    
        // Check if the source_name already exists in the database, except for the current record
        $count = LeadSource::where('source_name', $source_name)
                           ->where('id', '!=', $id)
                           ->count();
        if ($count > 0) {
            return redirect()->back()->with('error', 'Source name already exists');
        }
        $record->source_name = $source_name;
        $record->status = $request->input('status');
        $record->save();
        return redirect()->route('lead_source.index')->with('success', 'Record updated successfully');
    }    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadSource $leadSource)
    {
        //
    }
}
