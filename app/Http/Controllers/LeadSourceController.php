<?php

namespace App\Http\Controllers;

use App\Models\LeadSource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeadSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = LeadSource::orderBy('status','DESC')->whereIn('status',['active'])->get();
        return view('calls.lead_source',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calls.lead_source_create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    $record = LeadSource::findOrFail($id);
    return view('calls.lead_source_edit', ['record' => $record]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
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
