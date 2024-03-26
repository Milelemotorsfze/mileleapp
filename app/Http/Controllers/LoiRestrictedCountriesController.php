<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Country;
use App\Models\LoiRestrictedCountry;
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

        $loiRestrictedCountry = LoiRestrictedCountry::with('country')->orderBy('id','DESC')->get();

        if (request()->ajax()) {
            return DataTables::of($loiRestrictedCountry)
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
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

        return view('loi-restricted-countries.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required'
        ]);

        $LoiRestrictedCountry = new LoiRestrictedCountry();
        $LoiRestrictedCountry->country_id = $request->country_id;
        $LoiRestrictedCountry->comment = $request->comment;
        $LoiRestrictedCountry->updated_by = Auth::id();
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

        $alreadyAddedIds = LoiRestrictedCountry::whereNot('country_id', $LOIRestrictedCountry->country_id)->pluck('country_id');
        $countries = Country::whereNotIn('id', $alreadyAddedIds)->get();

        return view('loi-restricted-countries.edit', compact('countries','LOIRestrictedCountry'));
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
