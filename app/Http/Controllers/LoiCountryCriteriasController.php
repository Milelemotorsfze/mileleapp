<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\LoiCountryCriteria;
use App\Models\MasterModelLines;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class LoiCountryCriteriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        (new UserActivityController)->createActivity('Open LOI Restricted Counties List');

        $loiCountryCriteria = LoiCountryCriteria::with(['country','modelLine'])->orderBy('id','DESC')->get();

        if (request()->ajax()) {
            return DataTables::of($loiCountryCriteria)
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('master_model_line_id', function($query) {
                    return $query->modelLine->model_line ?? '';
                })
                ->editColumn('is_inflate_qty', function($query) {
                    if($query->is_inflate_qty == 1) {
                        return 'Yes';
                    }else if($query->is_inflate_qty == 2){
                        return 'No';
                    }else{
                        return 'None';
                    }
                })
                ->editColumn('is_longer_lead_time', function($query) {
                    if($query->is_longer_lead_time == 1) {
                        return 'Yes';
                    }else if($query->is_longer_lead_time == 2){
                        return 'No';
                    }else{
                        return 'None';
                    }
                })
                ->editColumn('is_loi_restricted', function($query) {
                    if($query->is_loi_restricted == true) {
                        return 'Yes';
                    }else{
                        return 'No';
                    }
                })
                ->editColumn('is_only_company_allowed', function($query) {
                    if($query->is_only_company_allowed == 1) {
                        return 'YES';
                    }else if($query->is_only_company_allowed == 2){
                        return 'No';
                    }else{
                        return 'None';
                    }
                })

                ->editColumn('updated_by', function($query) {
                    return $query->updatedBy->name ?? '';
                })
                ->addColumn('action', function(LoiCountryCriteria $loiCountryCriteria) {
                    return view('loi-country-criterias.action',compact('loiCountryCriteria'));
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'S.No'],
            ['data' => 'country.name', 'name' => 'country.name','title' => 'Country'],
            ['data' => 'status', 'name' => 'status','title' => 'Status'],
            ['data' => 'master_model_line_id', 'name' => 'master_model_line_id','title' => 'Restricted Model Line'],
            ['data' => 'is_inflate_qty', 'name' => 'is_inflate_qty','title' => 'Is Inflate Quantity'],
            ['data' => 'is_longer_lead_time', 'name' => 'is_longer_lead_time','title' => 'Longer Lead Time'],
            ['data' => 'is_loi_restricted', 'name' => 'is_loi_restricted','title' => 'Is LOI Restricted'],
            ['data' => 'is_only_company_allowed', 'name' => 'is_only_company_allowed','title' => 'Is Only Company Allowed'],
            ['data' => 'min_qty_per_passport', 'name' => 'min_qty_per_passport','title' => 'Minimum QTY/ Passport'],
            ['data' => 'max_qty_per_passport', 'name' => 'max_qty_per_passport','title' => 'Maximum QTY/ Passport'],
            ['data' => 'max_qty_for_company', 'name' => 'max_qty_for_company','title' => 'Maximum QTY/ Company'],
            ['data' => 'min_qty_for_company', 'name' => 'min_qty_for_company','title' => 'Minimum QTY/ Company'],
            ['data' => 'comment', 'name' => 'comment','title' => 'Comment'],
            ['data' => 'created_at', 'name' => 'created_at','title' => 'Created At'],
            ['data' => 'updated_by', 'name' => 'updated_by','title' => 'Updated By'],
            ['data' => 'action', 'name' => 'action','title' => 'Action'],
        ]);
        return view('loi-country-criterias.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alreadyAddedIds = LoiCountryCriteria::pluck('country_id');
        $countries = Country::whereNotIn('id', $alreadyAddedIds)->get();
        $modelLines = MasterModelLines::all();

        return view('loi-country-criterias.create', compact('countries','modelLines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required'
        ]);

//        dd($request->all());
        $loiCountryCriteria = new LoiCountryCriteria();
        $loiCountryCriteria->country_id = $request->country_id;
        $loiCountryCriteria->comment = $request->comment;
        $loiCountryCriteria->updated_by = Auth::id();
        $loiCountryCriteria->is_loi_restricted = $request->is_loi_restricted ? true : false;
        $loiCountryCriteria->is_only_company_allowed = $request->is_only_company_allowed;
        $loiCountryCriteria->is_inflate_qty = $request->is_inflate_qty;
        $loiCountryCriteria->is_longer_lead_time = $request->is_longer_lead_time;
        $loiCountryCriteria->min_qty_per_passport = $request->min_qty_per_passport;
        $loiCountryCriteria->max_qty_per_passport = $request->max_qty_per_passport;
        $loiCountryCriteria->max_qty_for_company = $request->max_qty_for_company;
        $loiCountryCriteria->min_qty_for_company = $request->min_qty_for_company;
        $loiCountryCriteria->master_model_line_id  = $request->master_model_line_id;
        $loiCountryCriteria->status = LoiCountryCriteria::STATUS_ACTIVE;

        $loiCountryCriteria->save();

        return redirect()->route('loi-country-criterias.index')->with('success','LOI Restricted Country Added Successfully.');

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
        $loiCountryCriteria = LoiCountryCriteria::find($id);

        $modelLines = MasterModelLines::all();
        $alreadyAddedIds = LoiCountryCriteria::whereNot('country_id', $loiCountryCriteria->country_id)->pluck('country_id');
        $countries = Country::whereNotIn('id', $alreadyAddedIds)->get();

        return view('loi-country-criterias.edit', compact('countries','loiCountryCriteria','modelLines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'country_id' => 'required'
        ]);

        $loiCountryCriteria =  LoiCountryCriteria::find($id);
        $loiCountryCriteria->country_id = $request->country_id;
        $loiCountryCriteria->comment = $request->comment;
        $loiCountryCriteria->updated_by = Auth::id();
        $loiCountryCriteria->is_loi_restricted = $request->is_loi_restricted ? true : false;
        $loiCountryCriteria->is_only_company_allowed = $request->is_only_company_allowed;
        $loiCountryCriteria->is_inflate_qty = $request->is_inflate_qty;
        $loiCountryCriteria->is_longer_lead_time = $request->is_longer_lead_time;
        $loiCountryCriteria->min_qty_per_passport = $request->min_qty_per_passport;
        $loiCountryCriteria->max_qty_per_passport = $request->max_qty_per_passport;
        $loiCountryCriteria->max_qty_for_company = $request->max_qty_for_company;
        $loiCountryCriteria->min_qty_for_company = $request->min_qty_for_company;
        $loiCountryCriteria->master_model_line_id  = $request->master_model_line_id;
        $loiCountryCriteria->save();

        return redirect()->route('loi-country-criterias.index')->with('success','LOI Country Criteria Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loiCountryCriteria = LoiCountryCriteria::find($id);
        $loiCountryCriteria->delete();

        return response(true);
    }
    public function statusChange(Request $request) {

        $loiCountryCriteria = LoiCountryCriteria::find($request->id);
        $loiCountryCriteria->status = $request->status;
        $loiCountryCriteria->save();

        return response(true);
    }
    public function CheckCountryCriteria(Request $request)
    {
        $LoiCountryCriteria = LoiCountryCriteria::where('country_id', $request->country_id)->where('status', LoiCountryCriteria::STATUS_ACTIVE)->first();

        $data = [];
        if(!empty($LoiCountryCriteria->comment)) {
            $data['comment'] = $LoiCountryCriteria->comment;
        }
        if(!empty($LoiCountryCriteria->is_only_company_allowed)) {
            if($LoiCountryCriteria->is_only_company_allowed == LoiCountryCriteria::YES ) {
                if($request->customer_type !== \App\Models\Customer::CUSTOMER_TYPE_COMPANY ) {
                    $data['customer_type_error'] = 'Only Company Can allow to Create LOI for this Country';
                }
            }
        }

        return response()->json($data);

    }
}
