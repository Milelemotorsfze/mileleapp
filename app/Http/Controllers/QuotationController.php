<?php

namespace App\Http\Controllers;

use App\Models\quotation;
use App\Models\Calls;
use App\Models\Brand;
use App\Models\Varaint;
use App\Models\Vehicles;
use App\Models\Vehiclescarts;
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
    $latestQuotation = quotation::where('created_by', auth()->user()->id)
                    ->latest()
                    ->first();
    $callsId = $latestQuotation->calls_id; 
    $data = Calls::select('name', 'email')
            ->where('id', $callsId)
            ->first();         
            $countries = CountryListFacade::getList('en');    
    return view('quotation.add_new', compact('data', 'countries'));
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
        $quotation = quotation::updateOrCreate([
            'calls_id' => $data->id, 
            'created_by' => auth()->user()->id
        ]);
     //   echo $quotation;
         $vehicles = Vehicles::query()
                     ->select('*')
                     ->addSelect('vehicles.id as veh_id')
                     ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                     ->join('brands', 'varaints.brands_id', '=', 'brands.id')
                     ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                     ->where('vehicles.status', '=', 'New')
                     ->get();
         $variants = Varaint::get();
         $brand = Brand::get();
         $countries = CountryListFacade::getList('en');
        // return view('quotation.add_new',compact('data', 'countries', 'variants', 'brand'));
        return view('quotation.sreach',compact('data', 'countries', 'variants', 'brand', 'vehicles', 'quotation'));
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
    public function addvehicles(Request $request)
    {
        if($request->actiond == "addvehicles"){
        $data = Vehiclescarts::updateOrCreate([
                'vehicle_id' => $request->vehicles_id, 
                'quotation_id' => $request->quotation_id,
                'created_by' => auth()->user()->id
            ]);
        return $data;    
        }
    }
}
