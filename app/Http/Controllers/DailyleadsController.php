<?php

namespace App\Http\Controllers;

use App\Models\Dailyleads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Calls;
use App\Models\CallsRequirement;
use Illuminate\Http\Request;
use App\Models\quotation;
use App\Models\Rejection;
use App\Models\Closed;
use App\Models\Brand;
use Carbon\Carbon;
use App\Models\MasterModelLines;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Logs;
use Illuminate\Support\Facades\Response;

class DailyleadsController extends Controller
{
    public function index(Request $request)
    {
       $id = Auth::user()->id;
       $pendingdata = Calls::where('status', 'New')->where('sales_person', $id)->get();
       $qutationsdata = Calls::where('status', 'Quoted')->where('sales_person', $id)->get();
       $rejectiondata = Calls::where('status', 'Rejected')->where('sales_person', $id)->get();
       $closeddata = Calls::where('status', 'Closed')->where('sales_person', $id)->get();
       $intialcallsdata = Calls::where('status', 'Initial Contact')->where('sales_person', $id)->get();
       return view('dailyleads.index',compact('pendingdata', 'intialcallsdata', 'qutationsdata', 'rejectiondata', 'closeddata'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = CountryListFacade::getList('en');
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        return view('dailyleads.create', compact('countries', 'modelLineMasters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $modelLineIds = $request->input('model_line_ids');
        dd($modelLineIds);
        $this->validate($request, [
            'phone' => 'nullable|required_without:email',
            'email' => 'nullable|required_without:phone|email',           
            'location' => 'required',
            'milelemotors' => 'required',
            'language' => 'required',
            'model_line_ids' => 'array',
            'model_line_ids.*' => 'distinct',
            'type' => 'required',
        ]);      
        $date = Carbon::now();
        $date->setTimezone('Asia/Dubai');
        $formattedDate = $date->format('Y-m-d H:i:s');
        $data = [
            'name' => $request->input('name'),
            // 'source' => $request->input('milelemotors'),
            'email' => $request->input('email'),
            'type' => $request->input('type'),
            'sales_person' => Auth::id(),
            'remarks' => $request->input('remarks'),
            'location' => $request->input('location'),
            'phone' => $request->input('phone'),
            'custom_brand_model' => $request->input('custom_brand_model'),
            'language' => $request->input('language'),
            'created_at' => $formattedDate,
            'created_by' => Auth::id(),
            'status' => "New",
            'customer_coming_type' => "Direct From Sales",
        ];
        $model = new Calls($data);
        $model->save();
        $lastRecord = Calls::where('created_by', $data['created_by'])
                   ->orderBy('id', 'desc')
                   ->where('sales_person', $id)
                   ->first();
        $table_id = $lastRecord->id;
        $modelLineIds = $request->input('model_line_ids');
        if ($modelLineIds[0] !== null) {
        foreach ($modelLineIds as $modelLineId) {
        $datacalls = [
        'lead_id' => $table_id,
        'model_line_id' => $modelLineId,
        'created_at' => $formattedDate
        ];
        $model = new CallsRequirement($datacalls);
        $model->save();
        }
        }
        $logdata = [
            'table_name' => "calls",
            'table_id' => $table_id,
            'user_id' => Auth::id(),
            'action' => "Create",
        ];
        $model = new Logs($logdata);
        $model->save();
        return redirect()->route('dailyleads.index')
        ->with('success','Lead Record created successfully');
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
   
    public function qoutations(Request $request)
{
    $validatedData = $request->validate([
        'date' => 'required|date',
        'dealValue' => 'required|numeric',
        'salesNotes' => 'nullable|string',
        'file' => 'nullable|mimes:pdf,doc,docx|max:2048',
    ]);
    $quotation = new quotation();
    $quotation->date = $validatedData['date'];
    $quotation->deal_value = $validatedData['dealValue'];
    $quotation->sales_notes = $validatedData['salesNotes'];
    $quotation->created_by = auth()->user()->id;
    $quotation->created_at = now();
    $quotation->calls_id = $request->callId;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('quotation_files', $filename, 'public');

        $quotation->file_path = $path;
    }
    $quotation->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Quoted';
    $call->save();
    return response()->json(['success' => true]);
}
public function rejection(Request $request)
{
    $rejection = new Rejection();
    $rejection->date = $request->date;
    $rejection->Reason = $request->reason;
    $rejection->sales_notes = $request->salesNotes;
    $rejection->created_by = auth()->user()->id;
    $rejection->created_at = now();
    $rejection->call_id = $request->callId;
    $rejection->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Rejected';
    $call->save();
    return response()->json(['success' => true]);
}

public function closed(Request $request)
{
    $Closed = new Closed();
    $Closed->date = $request->dealdate;
    $Closed->so_number = $request->sonumber;
    $Closed->sales_notes = $request->dealsalesNotes;
    $Closed->created_by = auth()->user()->id;
    $Closed->created_at = now();
    $Closed->call_id = $request->callId;
    $Closed->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Closed';
    $call->save();
    return response()->json(['success' => true]);
}
}
