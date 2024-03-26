<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Country;
use App\Models\LoiRestrictedCountry;
use App\Models\MasterModelLines;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class LoiRestrictedCountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        (new UserActivityController)->createActivity('Open LOI Restricted Counties List');

        $loiRestrictedCountry = LoiRestrictedCountry::with(['country','modelLine'])->orderBy('id','DESC')->get();

        if (request()->ajax()) {
            return DataTables::of($loiRestrictedCountry)
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('master_model_line_id', function($query) {
                    return $query->modelLine->model_line ?? '';
                })
                ->editColumn('is_inflate_qty', function($query) {
                   if($query->is_inflate_qty == true) {
                       return 'YES';
                   }else{
                       return 'NO';
                   }
                })
                ->editColumn('is_longer_lead_time', function($query) {
                    if($query->is_longer_lead_time == true) {
                        return 'YES';
                    }else{
                        return 'NO';
                    }
                })
                ->editColumn('is_loi_restricted', function($query) {
                    if($query->is_loi_restricted == true) {
                        return 'YES';
                    }else{
                        return 'NO';
                    }
                })
                ->editColumn('is_only_company_allowed', function($query) {
                    if($query->is_only_company_allowed == true) {
                        return 'YES';
                    }else{
                        return 'NO';
                    }
                })

                ->editColumn('updated_by', function($query) {
                    return $query->updatedBy->name ?? '';
                })
                ->addColumn('action', function(LoiRestrictedCountry $loiRestrictedCountry) {
                    return view('loi-restricted-countries.action',compact('loiRestrictedCountry'));
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
            ['data' => 'created_at', 'name' => 'created_at','title' => 'Created At'],
            ['data' => 'updated_by', 'name' => 'updated_by','title' => 'Updated By'],
            ['data' => 'action', 'name' => 'action','title' => 'Action'],
        ]);
        return view('loi-restricted-countries.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alreadyAddedIds = LoiRestrictedCountry::pluck('country_id');
        $countries = Country::whereNotIn('id', $alreadyAddedIds)->get();
        $modelLines = MasterModelLines::all();

        return view('loi-restricted-countries.create', compact('countries','modelLines'));
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
        $LoiRestrictedCountry = new LoiRestrictedCountry();
        $LoiRestrictedCountry->country_id = $request->country_id;
        $LoiRestrictedCountry->comment = $request->comment;
        $LoiRestrictedCountry->updated_by = Auth::id();
        $LoiRestrictedCountry->is_loi_restricted = $request->is_loi_restricted ? true : false;
        $LoiRestrictedCountry->is_only_company_allowed = $request->is_only_company_allowed ? true : false;
        $LoiRestrictedCountry->is_inflate_qty = $request->is_inflate_qty ? true : false;
        $LoiRestrictedCountry->is_longer_lead_time = $request->is_longer_lead_time ? true : false;
        $LoiRestrictedCountry->min_qty_per_passport = $request->min_qty_per_passport;
        $LoiRestrictedCountry->max_qty_per_passport = $request->max_qty_per_passport;
        $LoiRestrictedCountry->master_model_line_id  = $request->master_model_line_id;
        $LoiRestrictedCountry->status = LoiRestrictedCountry::STATUS_ACTIVE;

        $LoiRestrictedCountry->save();

        return redirect()->route('loi-restricted-countries.index')->with('success','LOI Restricted Country Added Successfully.');

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
        $LOIRestrictedCountry = LoiRestrictedCountry::find($id);

        $modelLines = MasterModelLines::all();
        $alreadyAddedIds = LoiRestrictedCountry::whereNot('country_id', $LOIRestrictedCountry->country_id)->pluck('country_id');
        $countries = Country::whereNotIn('id', $alreadyAddedIds)->get();

        return view('loi-restricted-countries.edit', compact('countries','LOIRestrictedCountry','modelLines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'country_id' => 'required'
        ]);

        $LoiRestrictedCountry =  LoiRestrictedCountry::find($id);
        $LoiRestrictedCountry->country_id = $request->country_id;
        $LoiRestrictedCountry->comment = $request->comment;
        $LoiRestrictedCountry->updated_by = Auth::id();
        $LoiRestrictedCountry->save();

        return redirect()->route('loi-restricted-countries.index')->with('success','LOI Restricted Country Added Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $LoiRestrictedCountry = LoiRestrictedCountry::find($id);
        $LoiRestrictedCountry->delete();

        return response(true);
    }
    public function statusChange(Request $request) {
        info($request->all());

        $LoiRestrictedCountry = LoiRestrictedCountry::find($request->id);
        $LoiRestrictedCountry->status = $request->status;
        $LoiRestrictedCountry->save();

        return response(true);
    }

}
