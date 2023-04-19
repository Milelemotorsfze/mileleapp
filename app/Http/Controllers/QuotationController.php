<?php

namespace App\Http\Controllers;

use App\Models\quotation;
use App\Models\Calls;
use App\Models\Brand;
use App\Models\Varaint;
use App\Models\MasterModelLines;
use Illuminate\Http\Request;
use Monarobase\CountryList\CountryListFacade;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Calls::where('id',$id)->first();
        $variants = Varaint::get();
        $brand = Brand::get();
        $countries = CountryListFacade::getList('en');
       // return view('quotation.add_new',compact('data', 'countries', 'variants', 'brand'));
       return view('quotation.sreach',compact('data', 'countries', 'variants', 'brand'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(){

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, quotation $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(quotation $quotation)
    {
        //
    }
    public function getmy(Request $request)
    {
        $data = Varaint::where('brands_id', $request->brand)
        ->groupBy('my')
        ->pluck('my')
        ->toArray();
    return $data;    
    }
    public function getmodelline(Request $request)
    {
        $masterModelLineids = Varaint::where('brands_id', $request->brand)
        ->where('my', $request->my)
        ->groupBy('master_model_lines_id')
        ->pluck('master_model_lines_id')
        ->toArray();
       $data = MasterModelLines::whereIn('id', $masterModelLineids)
       ->pluck('model_line')
       ->toArray();
       return $data;    
    }
    public function getsubmodel(Request $request)
    {
        $modellinearray = MasterModelLines::where('model_line', $request->model_line)
        ->pluck('id')
        ->toArray();
        $data = Varaint::where('brands_id', $request->brand)
        ->where('my', $request->my)
        ->whereIn('master_model_lines_id', $modellinearray)
        ->groupBy('sub_model')
        ->pluck('sub_model')
        ->toArray();
        return $data;       
    }
    public function gettrim(Request $request)
    {
        $modellinearray = MasterModelLines::where('model_line', $request->model_line)
        ->pluck('id')
        ->toArray();
        $data = Varaint::where('brands_id', $request->brand)
        ->where('my', $request->my)
        ->whereIn('master_model_lines_id', $modellinearray)
        ->groupBy('sub_model')
        ->pluck('sub_model')
        ->toArray();
        return $data;       
    }
}
