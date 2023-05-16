<?php

namespace App\Http\Controllers;

use App\Models\calls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelHasRoles;
use App\Models\SalesPersonLaugauges;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Brand;
use App\Models\LeadSource;
use App\Models\MasterModelLines;
use App\Models\Logs;
use App\Models\CallsRequirement;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class CallsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Calls::orderBy('status','DESC')->whereIn('status',['new','active'])->get();
        $convertedleads = Calls::where('status','Converted To Leads')->get();
        $convertedso = Calls::where('status','Converted To SO')->get();
        $convertedrejection = Calls::where('status','Rejection')->get();
        return view('calls.index',compact('data','convertedleads', 'convertedso','convertedrejection'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = CountryListFacade::getList('en');
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = ModelHasRoles::where('role_id', 3)->get();
        return view('calls.create', compact('countries', 'modelLineMasters', 'LeadSource', 'sales_persons',));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'phone' => 'nullable|required_without:email',
            'email' => 'nullable|required_without:phone|email',            
            'location' => 'required',
        ]);
        if($request->input('sales-option') == "auto-assign") {
        $email = $request->input('email');
        $phone = $request->input('phone');
        $language = $request->input('language');
        $sales_persons = ModelHasRoles::where('role_id', 3)->get();
        $sales_person_id = null;
        $existing_email_count = null;
        $existing_phone_count = null;
        $existing_language_count = null;
        foreach ($sales_persons as $sales_person) {
            if($language == "English") {
                $existing_email_count = Calls::where('email', $email)
                                             ->where('sales_person', $sales_person->model_id)
                                             ->whereNotNull('email')
                                             ->count();
                $existing_phone_count = Calls::where('phone', $phone)
                                             ->where('sales_person', $sales_person->model_id)
                                             ->whereNotNull('phone')
                                             ->count();
                if($existing_email_count != 0 || $existing_phone_count != 0) {
                    $sales_person_id = $sales_person->model_id;
                    break;
                } 
                else {
                    $new_calls_count = Calls::where('status', 'New')
                                             ->where('sales_person', $sales_person->model_id)
                                             ->count();
                    if ($new_calls_count < 5) {
                        $sales_person_id = $sales_person->model_id;
                        break;
                    }
                }
            } else {
                $existing_language_count = SalesPersonLaugauges::where('language', $language)
                                                                ->where('sales_person', $sales_person->model_id)
                                                                ->count(); 
                if($existing_language_count != 0){
                    $sales_person_id = $sales_person->model_id;
                    break;
                } else {
                    $new_calls_count = Calls::where('status', 'New')
                                             ->where('sales_person', $sales_person->model_id)
                                             ->count();
                    if ($new_calls_count < 5) {
                        $sales_person_id = $sales_person->model_id;
                        break;
                    }
                }
            }
        }
        if ($sales_person_id == null) {
            $sales_person_id = Calls::select('sales_person', DB::raw('COUNT(*) as count'))
                                     ->where('status', 'New')
                                     ->groupBy('sales_person')
                                     ->orderBy('count', 'ASC')
                                     ->first()
                                     ->sales_person;
        }  
    }
    else{
        $sales_person_id = $request->input('sales_person'); 
    }
        $date = Carbon::now();
        $date->setTimezone('Asia/Dubai');
        $formattedDate = $date->format('Y-m-d H:i:s');
        $data = [
            'name' => $request->input('name'),
            'source' => $request->input('source'),
            'email' => $request->input('email'),
            'sales_person' => $sales_person_id,
            'remarks' => $request->input('remarks'),
            'location' => $request->input('location'),
            'phone' => $request->input('phone'),
            'custom_brand_model' => $request->input('custom_brand_model'),
            'language' => $request->input('language'),
            'created_at' => $formattedDate,
            'created_by' => Auth::id(),
            'status' => "New",
        ];
        $model = new Calls($data);
        $model->save();
        $lastRecord = Calls::where('created_by', $data['created_by'])
                   ->orderBy('id', 'desc')
                   ->first();
        $table_id = $lastRecord->id;
        $modelLineIds = $request->input('model_line_id');

        foreach ($modelLineIds as $modelLineId) {
            $datacalls = [
                'lead_id' => $table_id,
                'model_line_id' => $modelLineId,
                'created_at' => $formattedDate
            ];
        
            $model = new CallsRequirement($datacalls);
            $model->save();
        }
        $logdata = [
            'table_name' => "calls",
            'table_id' => $table_id,
            'user_id' => Auth::id(),
            'action' => "Create",
        ];
        $model = new Logs($logdata);
        $model->save();
        return redirect()->route('calls.index')
        ->with('success','Call Record created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request, $call, $brand_id, $model_line_id, $location, $days, $custom_brand_model = null)
{   
    $brandId = $request->route('brand_id');
    $modelLineId = $request->route('model_line_id');
    $days = $request->route('days');
    $startDate = Carbon::now()->subDays($days)->startOfDay();
    $endDate = Carbon::now()->endOfDay();
    
    $callIds = DB::table('calls')
        ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->where('master_model_lines.brand_id', $brandId)
        ->where('master_model_lines.id', $modelLineId)
        ->whereBetween('calls.created_at', [$startDate, $endDate])
        ->pluck('calls.id');   
$data = Calls::orderBy('status', 'DESC')
    ->whereIn('id', $callIds)
    ->whereIn('status', ['new', 'active'])
    ->get();

return view('calls.resultbrand', compact('data'));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(calls $calls)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, calls $calls)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(calls $calls)
    {
        //
    }
    public function getmodelline(Request $request)
    {
        $brandId = $request->input('brand'); 
        $data = MasterModelLines::where('brand_id', $brandId)
            ->pluck('model_line', 'id');
        return response()->json($data);
    }
    public function createbulk()
    {
        $countries = CountryListFacade::getList('en');
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        return view('calls.createbulk', compact('countries','LeadSource'));
    }
    public function uploadingbulk(Request $request)
    {
        // Check if the file exists and is valid
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            // Get the file from the request
            $file = $request->file('file');
            // Convert the file to an array
            $rows = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX)[0];
            // Separate the headers from the data
            $headers = array_shift($rows);
            // Loop through each row and store the data in the database
            foreach ($rows as $row) {
                $call = new Calls();
                $name = $row[0];
                $phone = $row[1];
                $email =  $row[2];
                $language = $request->input('language');
                $sales_persons = ModelHasRoles::where('role_id', 3)->get();
                $sales_person_id = null;
                $existing_email_count = null;
                $existing_phone_count = null;
                $existing_language_count = null;
                foreach ($sales_persons as $sales_person) {
                    if($language == "English") {
                        $existing_email_count = Calls::where('email', $email)
                                                     ->where('sales_person', $sales_person->model_id)
                                                     ->whereNotNull('email')
                                                     ->count();
                        $existing_phone_count = Calls::where('phone', $phone)
                                                     ->where('sales_person', $sales_person->model_id)
                                                     ->whereNotNull('phone')
                                                     ->count();
                        if($existing_email_count != 0 || $existing_phone_count != 0) {
                            $sales_person_id = $sales_person->model_id;
                            break;
                        } 
                        else {
                            $new_calls_count = Calls::where('status', 'New')
                                                     ->where('sales_person', $sales_person->model_id)
                                                     ->count();
                            if ($new_calls_count < 5) {
                                $sales_person_id = $sales_person->model_id;
                                break;
                            }
                        }
                    } else {
                        $existing_language_count = SalesPersonLaugauges::where('language', $language)
                                                                        ->where('sales_person', $sales_person->model_id)
                                                                        ->count(); 
                        if($existing_language_count != 0){
                            $sales_person_id = $sales_person->model_id;
                            break;
                        } else {
                            $new_calls_count = Calls::where('status', 'New')
                                                     ->where('sales_person', $sales_person->model_id)
                                                     ->count();
                            if ($new_calls_count < 5) {
                                $sales_person_id = $sales_person->model_id;
                                break;
                            }
                        }
                    }
                }
                if ($sales_person_id == null) {
                    $sales_person_id = Calls::select('sales_person', DB::raw('COUNT(*) as count'))
                                             ->where('status', 'New')
                                             ->groupBy('sales_person')
                                             ->orderBy('count', 'ASC')
                                             ->first()
                                             ->sales_person;
                } 
                $date = Carbon::now();
                $date->setTimezone('Asia/Dubai');
                $formattedDate = $date->format('Y-m-d H:i:s');
                $call->name = $row[0];
                $call->phone = $row[1];
                $call->email = $row[2];
                $call->custom_brand_model = $row[3];
                $call->remarks = $row[4];
                $call->source = $request->input('source');
                $call->language = $request->input('language');
                $call->type = $request->input('type');
                $call->sales_person = $sales_person_id;
                $call->created_at = $formattedDate;
                $call->created_by = Auth::id();
                $call->status = "New";
                $call->location = "Location Not Mentioned";
                $call->save();
            }
            return redirect()->route('calls.index')
            ->with('success','Data uploaded successfully!');
        } else {
            return redirect()->route('calls.index')
            ->with('Error','The uploaded file is invalid.');
        }
    }
}