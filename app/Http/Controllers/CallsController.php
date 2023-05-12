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
use Carbon\Carbon;

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
            'model_line_id' => $request->input('model_line_id'),
            'brand_id' => $request->input('brand_id'),
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
        $logdata = [
            'table_name' => "calls",
            'table_id' => $table_id,
            'user_id' => Auth::id(),
            'action' => "Create",
        ];
        $log = new Logs($logdata);
        $model->save();
        return redirect()->route('calls.index')
        ->with('success','Call Record created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(calls $call, Request $request) 
{
    $brand_id = $request->route('brand_id');
    // $brand_id = $request->route('brand_id');
    // do something with $brand_id
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
    
}