<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvailableColour;
use App\Models\Varaint;
use App\Models\Vehicles;
use Illuminate\Http\Request;

class VariantPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $variantWithPricesIds = AvailableColour::pluck('varaint_id');
//        return $variantWithPricesIds;
        $variantWithoutPrices = Vehicles::whereNotIn('varaints_id', $variantWithPricesIds)->get();
        $variantWithPrices =  Vehicles::whereIn('varaints_id',$variantWithPricesIds)->get();

        return view('variant-prices.index', compact('variantWithoutPrices','variantWithPrices'));
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
        //
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
        $vehicle = Vehicles::find($id);
        return view('variant-prices.edit', compact('vehicle'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'price' => 'required'
        ]);

        $vehicle = Vehicles::find($id);

        $available_color = new AvailableColour();
        $available_color->varaint_id = $vehicle->varaints_id;
        $available_color->int_colour = $vehicle->int_colour;
        $available_color->ext_colour = $vehicle->ex_colour;
        $available_color->price = $request->price;
        $available_color->save();

        return redirect()->route('variant-prices.index')->with('success','Price Updated Succssfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
