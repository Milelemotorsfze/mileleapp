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
use App\Models\LeadSource;
use App\Models\Brand;
use App\Models\Prospecting;
use App\Models\Salesdemand;
use App\Models\Negotiation;
use Carbon\Carbon;
use App\Models\MasterModelLines;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Logs;
use Yajra\DataTables\DataTables; // Import DataTables from Yajra namespace
use Illuminate\Support\Facades\Response;

class DailyleadsController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $pendingdata = Calls::where('status', 'New')->where('sales_person', $id)->get();
        if ($request->ajax()) {
            $status = $request->input('status');
            $data = Calls::select(['calls.id', DB::raw("DATE_FORMAT(calls.created_at, '%d-%b-%Y') as created_at"), 'calls.type', 'calls.name', 'calls.phone', 'calls.email', 'calls.custom_brand_model', 'calls.location', 'calls.language', DB::raw("REPLACE(REPLACE(calls.remarks, '<p>', ''), '</p>', '') as remarks")])
                ->where('status', $status)->where('sales_person', $id);
            $data->addSelect(DB::raw('(SELECT GROUP_CONCAT(CONCAT(brands.brand_name, " - ", master_model_lines.model_line) SEPARATOR ", ") FROM calls_requirement
                JOIN master_model_lines ON calls_requirement.model_line_id = master_model_lines.id
                JOIN brands ON master_model_lines.brand_id = brands.id
                WHERE calls_requirement.lead_id = calls.id) as models_brands'));
            if ($status === 'Prospecting') {
                $data->addSelect(DB::raw("DATE_FORMAT(prospectings.date, '%d-%b-%Y') as date"), 'prospectings.salesnotes');
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
            } elseif ($status === 'New Demand') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%d-%b-%Y'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');                
                $data->addSelect(DB::raw("DATE_FORMAT(demand.date, '%d-%b-%Y') as ddate"), 'demand.salesnotes as dsalesnotes');
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
            } elseif ($status === 'Quoted') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%d-%b-%Y'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');  
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%d-%b-%Y'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');                
                $data->addSelect([
                    DB::raw("DATE_FORMAT(quotations.date, '%d-%b-%Y') as qdate"),
                    'quotations.sales_notes as qsalesnotes',
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(quotations.deal_value, ' ', quotations.currency) as ddealvalues"),
                ]);
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');                
            } elseif ($status === 'Negotiation') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%d-%b-%Y'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');  
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%d-%b-%Y'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id'); 
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%d-%b-%Y'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(quotations.deal_value, ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');                
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%d-%b-%Y'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');
            } elseif ($status === 'Closed') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%d-%b-%Y'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');  
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%d-%b-%Y'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id'); 
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%d-%b-%Y'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(quotations.deal_value, ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');                 
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%d-%b-%Y'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');               
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(lead_closed.date, '%d-%b-%Y'), '') as cdate"),
                    DB::raw("IFNULL(lead_closed.sales_notes, '') as csalesnotes"),
					DB::raw("IFNULL(lead_closed.so_number, '') as so_number"),
                    DB::raw("CONCAT(IFNULL(lead_closed.dealvalues, ''), ' ', IFNULL(lead_closed.currency, '')) as cdealvalues"),
                );
                $data->leftJoin('lead_closed', 'calls.id', '=', 'lead_closed.call_id');
            } elseif ($status === 'Rejected') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%d-%b-%Y'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');  
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%d-%b-%Y'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id'); 
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%d-%b-%Y'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(quotations.deal_value, ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');                
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%d-%b-%Y'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');               
                $data->addSelect(DB::raw("DATE_FORMAT(lead_rejection.date, '%d-%b-%Y') as rdate"), 'lead_rejection.sales_notes as rsalesnotes', 'lead_rejection.Reason as reason');
                $data->leftJoin('lead_rejection', 'calls.id', '=', 'lead_rejection.call_id');
            }
            $data->groupBy('calls.id');
            return DataTables::of($data)
                ->addColumn('models_brands', function ($row) {
                    return $row->models_brands;
                })
                ->toJson();
        }    
        return view('dailyleads.index', compact('pendingdata'));
    }
    public function create()
    {
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $countries = CountryListFacade::getList('en');
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        return view('dailyleads.create', compact('countries', 'modelLineMasters', 'LeadSource'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $modelLineIdsRaw = $request->input('model_line_ids');
        $modelLineIds = json_decode($modelLineIdsRaw, true);
        $modelLineIds = array_map('strval', $modelLineIds);
        $this->validate($request, [
            'phone' => 'nullable|required_without:email',
            'email' => 'nullable|required_without:phone|email',           
            'location' => 'required',
            'milelemotors' => 'required',
            'language' => 'required',
            'type' => 'required',
        ]);      
        $date = Carbon::now();
        $date->setTimezone('Asia/Dubai');
        $formattedDate = $date->format('Y-m-d H:i:s');
        $dataValue = LeadSource::where('source_name', $request->input('milelemotors'))->value('id');
        $data = [
            'name' => $request->input('name'),
            'source' => $dataValue,
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
        $calls = new Calls($data);
        $calls->save();
        $lastRecord = Calls::where('created_by', $data['created_by'])
                   ->orderBy('id', 'desc')
                   ->where('sales_person', Auth::id())
                   ->first();
        $table_id = $lastRecord->id;
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
        'currency' => 'nullable|string',
    ]);
    $quotation = new quotation();
    $quotation->date = $validatedData['date'];
    $quotation->deal_value = $validatedData['dealValue'];
    $quotation->sales_notes = $validatedData['salesNotes'];
    $quotation->currency = $validatedData['currency'];
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
    $Closed->date = $request->date;
    $Closed->so_number = $request->sonumber;
    $Closed->sales_notes = $request->salesNotes;
    $Closed->dealvalues = $request->dealvalues;
    $Closed->currency = $request->currency;
    $Closed->created_by = auth()->user()->id;
    $Closed->created_at = now();
    $Closed->call_id = $request->callId;
    $Closed->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Closed';
    $call->save();
    return response()->json(['success' => true]);
}
public function savenegotiation(Request $request)
{
    $negotiation = new Negotiation();
    $negotiation->date = $request->date;
    $negotiation->sales_notes = $request->salesNotes;
    $negotiation->dealvalues = $request->dealvalues;
    $negotiation->currency = $request->currency;
    $negotiation->created_by = auth()->user()->id;
    $negotiation->created_at = now();
    $negotiation->calls_id = $request->callId;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('negotiation_files', $filename, 'public');
        $negotiation->file_path = $path;
    }
    $negotiation->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Negotiation';
    $call->save();
    return response()->json(['success' => true]);
}
public function saveprospecting(Request $request)
	{
    $validatedData = $request->validate([
        'date' => 'required|date',
        'salesNotes' => 'nullable|string',
    ]);
    $prospecting = new Prospecting();
    $prospecting->date = $validatedData['date'];
    $prospecting->salesnotes = $validatedData['salesNotes'];
    $prospecting->created_by = auth()->user()->id;
    $prospecting->created_at = now();
    $prospecting->calls_id = $request->callId;
    $prospecting->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Prospecting';
    $call->save();
    return response()->json(['success' => true]);
	}
    public function savedemand(Request $request)
	{
    $validatedData = $request->validate([
        'date' => 'required|date',
        'salesNotes' => 'nullable|string',
    ]);
    $demands = new Salesdemand();
    $demands->date = $validatedData['date'];
    $demands->salesnotes = $validatedData['salesNotes'];
    $demands->created_by = auth()->user()->id;
    $demands->created_at = now();
    $demands->calls_id = $request->callId;
    $demands->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'New Demand';
    $call->save();
    return response()->json(['success' => true]);
	}
}
