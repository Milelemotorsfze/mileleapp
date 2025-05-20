<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserActivityController;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        (new UserActivityController)->createActivity('Open Brand Listing Page');
        $brand = Brand::orderBy('id','DESC')->get();
        if (request()->ajax()) {
            return DataTables::of($brand)
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('created_by', function($query) {
                    return $query->CreatedBy->name ?? '';
                })
                ->addColumn('action', function(Brand $brand) {
                    return view('brands.action',compact('brand'));
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'S.No'],
            ['data' => 'brand_name', 'name' => 'brand_name','title' => 'Name'],
            ['data' => 'created_at', 'name' => 'created_at','title' => 'Created At'],
            ['data' => 'created_by', 'name' => 'created_by','title' => 'Created By'],
            ['data' => 'action', 'name' => 'action','title' => 'Action'],

        ]);
        return view('brands.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open Create New Brand');
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('Create New Brand');

        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|unique:brands,brand_name',
        ]);
        if ($validator->fails() ) {
            if($request->request_from == 'Quotation') {
                $data['error'] = $validator->messages()->first();
                return response($data);
            }else{
                return redirect()->back()->withErrors($validator);

            }

        }
        $brand = new Brand();
        $brand->brand_name = $request->brand_name;
        $brand->created_by = Auth::id();
        $brand->save();

        (new UserActivityController)->createActivity('Create New Brand');

        if($request->request_from == 'Quotation') {
            return response($brand);
        }

        return redirect()->route('brands.index')->with('success','Brand Created Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        (new UserActivityController)->createActivity('Open Brand Edit Page');
        $brand = Brand::findOrFail($brand->id);

        return view('brands.edit',compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        (new UserActivityController)->createActivity('update Brand Information');
        $this->validate($request,[
            'brand_name' => 'required|unique:brands,brand_name,'.$brand->id,
        ]);

        $brand = Brand::findOrFail($brand->id);
        $brand->brand_name = $request->brand_name;
        $brand->updated_by = Auth::id();
        $brand->save();

        return redirect()->route('brands.index')->with('success','Brand Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
