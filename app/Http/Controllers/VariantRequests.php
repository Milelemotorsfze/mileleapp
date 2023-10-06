<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariantRequest;
use App\Models\Varaint;


class VariantRequests extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $variant = $request->input('variant');
        $brands_id = $request->input('brands_id');
        $master_model_lines_id = $request->input('master_model_lines_id');
        $modelDescription = $request->input('model_description');
        $modelYear = $request->input('model_year');
        $variantDetails = $request->input('variant_details');
        $engine_capacity = $request->input('engine_capacity');
        $transmission = $request->input('transmission');
        $fuel_type = $request->input('fuel_type');
        $steering = $request->input('steering');
        $seat_capacity = $request->input('seat_capacity');
        $upholstery = $request->input('upholstery');
        $variantRequest = new VariantRequest();
        $variantRequest->name = $variant;
        $variantRequest->model_detail = $modelDescription;
        $variantRequest->my = $modelYear;
        $variantRequest->detail = $variantDetails;
        $variantRequest->engine = $engine_capacity;
        $variantRequest->gearbox = $transmission;
        $variantRequest->fuel_type = $fuel_type;
        $variantRequest->steering = $steering;
        $variantRequest->seat = $seat_capacity;
        $variantRequest->upholestry = $upholstery;
        $variantRequest->master_model_lines_id = $master_model_lines_id;
        $variantRequest->brands_id = $brands_id;
        $variantRequest->status = 'Pending';
        $variantRequest->save();
        return response()->json(['message' => 'Data saved successfully']);  
    }

    public function checkVariant(Request $request)
    {
        $variantName = $request->input('name');
        $exists = Varaint::where('name', $variantName)->exists();
        if(!$exists){
            $exists = VariantRequest::where('name', $variantName)->where('status', 'Pending')->exists(); 
        }
        return response()->json(['exists' => $exists]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function checkVariantorg(Request $request)
    {
        $variantName = $request->input('name');
        $exists = Varaint::where('name', $variantName)->exists();
        return response()->json(['exists' => $exists]);
    } 
}
