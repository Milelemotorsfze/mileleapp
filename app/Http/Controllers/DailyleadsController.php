<?php

namespace App\Http\Controllers;

use App\Models\Dailyleads;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivities;
use Illuminate\Support\Facades\Auth;
use App\Models\Calls;
use App\Models\CallsRequirement;
use App\Models\ModelHasRoles;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Rejection;
use App\Models\Closed;
use App\Models\LeadSource;
use App\Models\Brand;
use App\Models\Fellowup;
use App\Models\PreOrder;
use App\Models\So;
use App\Models\Prospecting;
use App\Models\Salesdemand;
use App\Models\SalespersonOfClients;
use App\Models\Negotiation;
use App\Models\Booking;
use App\Models\Clients;
use App\Models\ClientLeads;
use Carbon\Carbon;
use App\Models\MasterModelLines;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Logs;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class DailyleadsController extends Controller
{
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open the Daily Leads Section";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $id = Auth::user()->id;
        $clients = SalespersonOfClients::with('client')
        ->where('sales_person_id', $id)
        ->get();
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
        if($hasPermission)
        {
        $pendingdata = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
    ->where('calls.status', 'New')
    ->whereNull('calls.leadtype')
    ->orderByRaw("FIELD(calls.priority, 'Low', 'Normal', 'Hot') DESC")
    ->select('calls.*')
    ->get();
    }
    else
    {
        $pendingdata = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
        ->where('calls.status', 'New')
        ->where('calls.sales_person', $id)
        ->whereNull('calls.leadtype')
        ->orderByRaw("FIELD(calls.priority, 'Low', 'Normal', 'Hot') DESC")
        ->orderBy('calls.created_by', 'desc')
        ->select('calls.*')
        ->get();
    }
        if ($request->ajax()) {
            $status = $request->input('status');
            if($status === "Closed")
            {
                $so = so::select([
                    'calls.name as customername',
                    'calls.email',
                    'calls.phone',
                    'quotations.created_at',
                    'quotations.deal_value',
                    'quotations.sales_notes',
                    'quotations.file_path',
                    'users.name',
                    'so.so_number',
                    'so.so_date',
                ])
                ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
                ->leftJoin('users', 'quotations.created_by', '=', 'users.id')
                ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
                ->groupby('so.id')
                ->get();
                return DataTables::of($so)->toJson();  
            }
            if($status === "Preorder")
            {
                $preorders = PreOrder::select([
                    'pre_orders.status as status',
                    'quotations.id as quotationsid',
                    \DB::raw("DATE_FORMAT(quotations.date, '%Y %m %d') as date_formatted"),
                    'quotations.deal_value as deal_value',
                    'quotations.sales_notes as sales_notes',
                    'master_model_lines.model_line as model_line',
                    'pre_orders_items.qty',
                    'pre_orders_items.description',
                    'countries.name as countryname',
                    'color_codes_exterior.name as exterior',
                    'color_codes_interior.name as interior',
                    'pre_orders_items.modelyear'
                ])
                ->leftJoin('quotations', 'pre_orders.quotations_id', '=', 'quotations.id')
                ->leftJoin('pre_orders_items', 'pre_orders.id', '=', 'pre_orders_items.preorder_id')
                ->leftJoin('master_model_lines', 'pre_orders_items.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('countries', 'pre_orders_items.countries_id', '=', 'countries.id')
                ->leftJoin('color_codes as color_codes_exterior', 'pre_orders_items.ex_colour', '=', 'color_codes_exterior.id')
                ->leftJoin('color_codes as color_codes_interior', 'pre_orders_items.int_colour', '=', 'color_codes_interior.id')
                ->where('quotations.created_by', $id)
                ->groupby('pre_orders.id')
                ->get();
                return DataTables::of($preorders)->toJson();  
            }
            else if($status === "followup")
            {
                $fellowup = Fellowup::select([
                    'calls.id',
                    'fellow_up.time',
                    \DB::raw("DATE_FORMAT(fellow_up.date, '%Y %m %d') as datefol"),
                    'fellow_up.method',
                    'calls.name',
                    'calls.phone',
                    'calls.email',
                    'calls.remarks',
                    'calls.type',
                    'calls.location',
                    'calls.language',
                    'master_model_lines.model_line',
                    'brands.brand_name',
                    \DB::raw("DATE_FORMAT(calls.created_at, '%Y %m %d') as leaddate"),
                    'fellow_up.sales_notes'
                ])
                ->leftJoin('calls', 'fellow_up.calls_id', '=', 'calls.id')
                ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->where('calls.sales_person', $id)
                ->orderBy('fellow_up.date')
                ->orderBy('fellow_up.time')
                ->groupby('calls.id')
                ->get();
                return DataTables::of($fellowup)->toJson();  
            }
            else if($status === "bulkleads")
            {
                $bulkleads = calls::select([
                    'calls.id',
                    'calls.name',
                    'calls.phone',
                    'calls.email',
                    'calls.remarks',
                    'calls.type',
                    'calls.location',
                    'users.name as createdby',
                    'calls.language',
                    'master_model_lines.model_line',
                    'brands.brand_name',
                    'calls.remarks',
                    \DB::raw("DATE_FORMAT(calls.created_at, '%Y %m %d') as leaddate"),
                ])
                ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                ->leftJoin('users', 'calls.sales_person', '=', 'users.id')
                ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->where('calls.sales_person', $id)
                ->whereNotNull('calls.leadtype')
                ->groupby('calls.id')
                ->get();
                return DataTables::of($bulkleads)->toJson();   
            }
            else
            {
            $searchValue = $request->input('search.value');
            $data = Calls::select(['calls.id',DB::raw("DATE_FORMAT(calls.created_at, '%Y-%m-%d') as created_at"), 'calls.type', 'calls.name', 'calls.phone', 'calls.email', 'calls.custom_brand_model', 'calls.location', 'calls.language', DB::raw("REPLACE(REPLACE(calls.remarks, '<p>', ''), '</p>', '') as remarks")]);
            if($status === "Prospecting")
            {
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
                if($hasPermission)
                {
                    $data->whereIn('calls.status', ['Prospecting', 'New Demand'])->orderBy('created_at', 'desc');
                }
                else
                {
                    $data->whereIn('calls.status', ['Prospecting', 'New Demand'])->where('sales_person', $id)->orderBy('created_at', 'desc');
                }
            }
            else
            {
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
                if($hasPermission)
                {
                    $data->where('calls.status', $status)->orderBy('created_at', 'desc');
                
                }
                else
                {
                    $data->where('calls.status', $status)->whereNull('calls.leadtype')->where('sales_person', $id)->orderBy('created_at', 'desc');
                }
            }
            $data->addSelect(DB::raw('(SELECT GROUP_CONCAT(CONCAT(brands.brand_name, " - ", master_model_lines.model_line) SEPARATOR ", ") FROM calls_requirement
                JOIN master_model_lines ON calls_requirement.model_line_id = master_model_lines.id
                JOIN brands ON master_model_lines.brand_id = brands.id
                WHERE calls_requirement.lead_id = calls.id) as models_brands'));
                if (!empty($searchValue)) {
                    $data->where(function ($query) use ($searchValue) {
                        $query->where('calls.name', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.created_at', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.email', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.phone', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.custom_brand_model', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.location', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.language', 'LIKE', "%$searchValue%")
                            ->orWhereExists(function ($subquery) use ($searchValue) {
                                $subquery->select(DB::raw(1))
                                    ->from('calls_requirement')
                                    ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                                    ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                                    ->whereRaw('calls_requirement.lead_id = calls.id')
                                    ->where(function ($subquery) use ($searchValue) {
                                        $subquery->whereRaw('LOWER(brands.brand_name) LIKE ?', ["%" . strtolower($searchValue) . "%"])
                                            ->orWhereRaw('LOWER(master_model_lines.model_line) LIKE ?', ["%" . strtolower($searchValue) . "%"]);
                                    });
                            });
                    });
                }
                if ($status === 'Prospecting') {
                    $data->addSelect(DB::raw("DATE_FORMAT(prospectings.date, '%Y %m %d') as date"), 'prospectings.salesnotes');
                    $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                    $data->addSelect(
                        DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                        DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes"),
                        DB::raw("IFNULL(demand.purchaserremarks, '') as purchaserremarks"),
                    );
                    $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                } elseif ($status === 'New Demand') {
                    $data->addSelect(
                        DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                        DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                    );
                    $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                    $data->addSelect(DB::raw("DATE_FORMAT(demand.date, '%Y %m %d') as ddate"), 'demand.salesnotes as dsalesnotes');
                    $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                } elseif ($status === 'Quoted') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect([
                    DB::raw("DATE_FORMAT(quotations.date, '%Y %m %d') as qdate"),
                    'quotations.sales_notes as qsalesnotes',
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as ddealvalues"), ('quotations.signature_status as signature_status'),
                    'users.name as salespersonname',
                ]);
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->leftJoin('users', 'quotations.created_by', '=', 'users.id');
            } elseif ($status === 'Negotiation') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%Y %m %d'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%Y %m %d'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');
            } elseif ($status === 'Closed') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%Y %m %d'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%Y %m %d'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(lead_closed.date, '%Y %m %d'), '') as cdate"),
                    DB::raw("IFNULL(lead_closed.sales_notes, '') as csalesnotes"),
                    DB::raw("IFNULL(lead_closed.so_id, '') as so_id"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as cdealvalues"),
                    'users.name as salespersonname',
                );
                $data->leftJoin('lead_closed', 'calls.id', '=', 'lead_closed.call_id');
                $data->leftJoin('so', function ($join) {
                    $join->on('lead_closed.so_id', '=', 'so.id')
                         ->whereNotNull('lead_closed.so_id');
                });
                $data->addSelect('so.so_number');
                $data->leftJoin('users', 'quotations.created_by', '=', 'users.id');
            } elseif ($status === 'Rejected') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%Y %m %d'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(quotations.deal_value, ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%Y %m %d'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');
                $data->addSelect(DB::raw("DATE_FORMAT(lead_rejection.date, '%Y %m %d') as rdate"), 'lead_rejection.sales_notes as rsalesnotes', 'lead_rejection.Reason as reason');
                $data->leftJoin('lead_rejection', 'calls.id', '=', 'lead_rejection.call_id');
            }
            $data->groupBy('calls.id');
            $results = $data->get();
            return DataTables::of($results)
                ->addColumn('models_brands', function ($row) {
                    return $row->models_brands;
                })
                ->toJson();
        }
    }
        return view('dailyleads.index', compact('pendingdata', 'clients'));
    }
    public function create()
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create the New Direct Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $countries = CountryListFacade::getList('en');
        $salespersonId = auth()->user()->id;
        $clients = SalespersonOfClients::with('client')
        ->where('sales_person_id', $salespersonId)
        ->get();
        $sales_persons = ModelHasRoles::where('role_id', 7)->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        return view('dailyleads.create', compact('modelLineMasters', 'clients', 'countries','sales_persons'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Store the New Direct Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $modelLineIdsRaw = $request->input('model_line_ids');
        if($modelLineIdsRaw)
        {
        $modelLineIds = json_decode($modelLineIdsRaw, true);
        $modelLineIds = array_map('strval', $modelLineIds);
        }
        $assignid = $request->input('assignto');
        $salesPersonId = $assignid ? $assignid : Auth::id();
        $client = $request->input('client_id');
        if(!$client)
        {
            $date = Carbon::now();
            $date->setTimezone('Asia/Dubai');
            $dataValue = '40';
            $formattedDate = $date->format('Y-m-d H:i:s');
            $data = [
                'source' => $dataValue,
                'type' => $request->input('type'),
                'sales_person' => $salesPersonId,
                'remarks' => $request->input('remarks'),
                'custom_brand_model' => $request->input('custom_brand_model'),
                'created_at' => $formattedDate,
                'assign_time' => $formattedDate,
                'created_by' => Auth::id(),
                'leadtype' => $request->input('leadtype'),
                'status' => "New",
                'priority' => "High",
                'customer_coming_type' => "Direct From Sales",
            ];
            $calls = new Calls($data);
            $calls->save();
        }
        else
        {
        $client = Clients::find($request->input('client_id'));
        $date = Carbon::now();
        $date->setTimezone('Asia/Dubai');
        $dataValue = LeadSource::where('source_name', $client->source)->value('id');
        $formattedDate = $date->format('Y-m-d H:i:s');
        $data = [
            'name' => $client->name,
            'source' => $dataValue,
            'email' => $client->email,
            'type' => $request->input('type'),
            'sales_person' => $salesPersonId,
            'remarks' => $request->input('remarks'),
            'location' => $client->destination,
            'phone' => $client->phone,
            'custom_brand_model' => $request->input('custom_brand_model'),
            'language' => $client->lauguage,
            'created_at' => $formattedDate,
            'assign_time' => $formattedDate,
            'created_by' => Auth::id(),
            'status' => "New",
            'priority' => "High",
            'customer_coming_type' => "Direct From Sales",
        ];
        $calls = new Calls($data);
        $calls->save();
        $clientleads = New ClientLeads();
        $clientleads->calls_id = $calls->id; 
        $clientleads->clients_id = $client->id;
        $clientleads->save();
        }
        $lastRecord = Calls::where('created_by', $data['created_by'])
                   ->orderBy('id', 'desc')
                   ->where('sales_person', Auth::id())
                   ->first();
        $table_id = $lastRecord->id;
        if($modelLineIdsRaw)
        {
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
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead Status";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead to Qoutation";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $validatedData = $request->validate([
        'date' => 'required|date',
        'dealValue' => 'nullable|numeric',
        'salesNotes' => 'nullable|string',
        'currency' => 'nullable|string',
    ]);
    $quotation = new quotation();
    $quotation->date = $validatedData['date'];
    $quotation->deal_value = isset($validatedData['dealValue']) ? $validatedData['dealValue'] : '';
    $quotation->sales_notes = isset($validatedData['salesNotes']) ? $validatedData['salesNotes'] : '';
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
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the lead into Rejection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $rejection = new Rejection();
    $rejection->date = $request->date;
    $rejection->Reason = $request->reason;
    $rejection->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
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
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the lead into Sales Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $sonumber = $request->sonumber;
    $so = So::where('so_number', $sonumber)->first();
    if (!$so) {
        $so = new So();
        $so->so_number = $sonumber;
        $so->sales_person_id = auth()->user()->id;
        $so->so_date = $request->date;
        $so->created_at = now();
        $so->notes = $request->has('salesNotes') ? $request->salesNotes : '';
        $so->save();
    }
    $Closed = new Closed();
    $Closed->date = $request->date;
    $Closed->so_id = $so->id;
    $Closed->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
    $Closed->dealvalues = $request->has('dealvalues') ? $request->dealvalues : '';
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
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Negotiation";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $negotiation = new Negotiation();
    $negotiation->date = $request->date;
    $negotiation->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
    $negotiation->dealvalues = $request->has('dealvalues') ? $request->dealvalues : '';
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
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Prospecting";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $validatedData = $request->validate([
        'date' => 'required|date',
        'salesNotes' => 'nullable|string',
    ]);
    $prospecting = new Prospecting();
    $prospecting->date = $validatedData['date'];
    $prospecting->salesnotes =  isset($validatedData['salesNotes']) ? $validatedData['salesNotes'] : '';
    $prospecting->created_by = auth()->user()->id;
    $prospecting->created_at = now();
    $prospecting->calls_id = $request->callId;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('prospecting', $filename, 'public');
        $prospecting->file_path = $path;
    }
    $prospecting->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Prospecting';
    $call->priority = $request->has('priority') ? $request->priority : '';
    $call->save();
    $existingclients = Clients::where('phone', $call->phone)->orwhere('email', $call->email);
    $existingleads = ClientLeads::where('calls_id', $call->id);
    if(!$existingleads)
    {
    if($existingclients)
    {
        $clientleads = New ClientLeads();
        $clientleads->calls_id = $call->id; 
        $clientleads->clients_id = $existingclients->id;
        $clientleads->save();  
    }
    else
    {
        $client = New Clients();
        $client->name = $call->name;
        $client->phone = $call->phone;
        $client->email = $call->email;
        $client->source = $call->source;
        $client->lauguage = $call->language;
        $client->destination = $call->location;
        $client->save();
        $clientleads = New ClientLeads();
        $clientleads->calls_id = $call->id; 
        $clientleads->clients_id =  $client->id;
        $clientleads->save();  
    }
    }
    return response()->json(['success' => true]);
	}
    public function savedemand(Request $request)
	{
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change Lead into Demand";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $validatedData = $request->validate([
        'date' => 'required|date',
        'salesNotes' => 'nullable|string',
    ]);
    $demands = new Salesdemand();
    $demands->date = $validatedData['date'];
    $demands->salesnotes =  isset($validatedData['salesNotes']) ? $validatedData['salesNotes'] : '';
    $demands->created_by = auth()->user()->id;
    $demands->created_at = now();
    $demands->calls_id = $request->callId;
    $demands->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'New Demand';
    $call->save();
    return response()->json(['success' => true]);
	}
    public function leadspage($calls_id)
    {
    $calls = Calls::find($calls_id);
    $prospecting = Prospecting::where('calls_id', $calls_id)->get();
    $quotations = quotation::where('calls_id', $calls_id)->get();
    $negotiations = Negotiation::where('calls_id', $calls_id)->get();
    $closed = Closed::where('call_id', $calls_id)->get();
    $bookingDetails = Booking::join('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
    ->where('booking.calls_id', $calls_id)
    ->select('booking.*', 'vehicles.*', 'brands.brand_name', 'varaints.name', 'master_model_lines.model_line')
    ->groupby('booking.id')
    ->get();
    $demands = Salesdemand::where('calls_id', $calls_id)->get();
    return view('dailyleads.singleleadview', compact('calls', 'prospecting', 'quotations', 'demands', 'negotiations', 'closed', 'bookingDetails'));
    }
    public function savefollowup(Request $request)
	{
        info($request->date);
        $callsid = $request->callId;
        $callupdate = Calls::find($callsid);
        $callupdate->status = "Follow Up";
        $callupdate->save();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Follow Up";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $followup = new Fellowup();
        $followup->date = $request->has('date') ? $request->date : '';
        $followup->time = $request->has('time') ? $request->time : '';
        $followup->method = $request->has('method') ? $request->method : '';
        $followup->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
        $followup->calls_id = $callsid;
        $followup->save();
        return response()->json(['success' => true]);
	}
    public function savefollowupdate(Request $request)
	{
        info($request->date);
        $callsid = $request->callId;
        $callupdate = Calls::find($callsid);
        $callupdate->status = "Follow Up";
        $callupdate->save();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Follow Up";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $followup = Fellowup::where('calls_id', $callsid)->first();
        $followup->date = $request->has('date') ? $request->date : '';
        $followup->time = $request->has('time') ? $request->time : '';
        $followup->method = $request->has('method') ? $request->method : '';
        $followup->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
        $followup->save();
        return response()->json(['success' => true]);
	} 
    public function followupgetdata($id)
    {
        $data = Fellowup::where('calls_id', $id)->first();
        return response()->json($data);
    } 
    public function checkAuthorization(Request $request)
{
    $call = Calls::find($request->call_id);
    if ($call && $call->sales_person == auth()->user()->id) {
        return response()->json(['authorized' => true]);
    } else {
        return response()->json(['authorized' => false]);
    }
}
public function updateCallClient(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'call_id' => 'required|exists:calls,id',
    ]);
    $client = Clients::find($request->client_id);
    if (!$client) {
        return response()->json(['message' => 'Client not found!'], 404);
    }
    $call = Calls::find($request->call_id);
    if (!$call) {
        return response()->json(['message' => 'Call not found!'], 404);
    }
    $call->name = $client->name;
    $call->phone = $client->phone;
    $call->email = $client->email;
    $call->language = $client->lauguage;
    $call->location = $client->destination;
    $call->save();
    $clientLead = new ClientLeads();
    $clientLead->clients_id = $request->client_id;
    $clientLead->calls_id = $request->call_id;
    $clientLead->save();
    return response()->json(['message' => 'Client updated successfully!'], 200);
}
}
