<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvailableColour;
use App\Models\Varaint;
use App\Models\VehiclePriceHistory;
use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VariantPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $vehicleWithoutPrices = Vehicles::whereNull('price')
                                ->groupBy('varaints_id')
                                ->selectRaw('count(*) as total,id, varaints_id, int_colour, ex_colour, price')->get();
        $vehicleWithPrices =  Vehicles::whereNotNull('price')
                                ->groupBy('varaints_id')
                                ->selectRaw('count(*) as total,id, varaints_id, int_colour, ex_colour, price')
                                ->get();

        return view('variant-prices.index', compact('vehicleWithoutPrices','vehicleWithPrices'));
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
    public function edit(string $id, string $type)
    {
        $vehicle = Vehicles::find($id);

        $variantPrices = AvailableColour::where('varaint_id', $vehicle->varaints_id)->pluck('id');
        $variantPriceHistories = VehiclePriceHistory::whereHas('availableColour', function ($query) {
            $query->orderBy('varaint_id','DESC');
        })
        ->whereIn('available_colour_id', $variantPrices)->get();
        if($type == 1) {
            $vehicles =  $vehicle->similar_vehicles_with_price;

        }else{
            $vehicles =  $vehicle->similar_vehicles_without_price;

       }

        return view('variant-prices.edit', compact('vehicle','variantPriceHistories','vehicles'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        return $request->all();
        $request->validate([
            'prices' => 'required'
        ]);

        $prices = $request->prices;
        $vehicles = $request->vehicle_ids;

        foreach ($prices as $key => $price ) {
            $vehicle = Vehicles::find($vehicles[$key]);
            if($price != $vehicle->price) {
                info("new price update".$vehicle->id);
                $vehicle->price = $price;
                $vehicle->save();

                $available_color = AvailableColour::where('varaint_id', $vehicle->varaints_id)
                    ->where('int_colour', $vehicle->int_colour)
                    ->where('ext_colour', $vehicle->ex_colour)
                    ->first();

                if(empty($available_color)) {
                    $available_color = new AvailableColour();
                    $oldPrice = Null;
                    $status = 'New';
                }else{
                    $oldPrice = $available_color->price;
                    $status = 'Updated';
                }

                $available_color->varaint_id = $vehicle->varaints_id;
                $available_color->int_colour = $vehicle->int_colour;
                $available_color->ext_colour = $vehicle->ex_colour;
                $available_color->price = $price;
                $available_color->updated_by = Auth::id();
                $available_color->save();

                $vehiclePriceHistory = new VehiclePriceHistory();
                $vehiclePriceHistory->available_colour_id  = $available_color->id;
                $vehiclePriceHistory->old_price = $oldPrice;
                $vehiclePriceHistory->new_price = $price;
                $vehiclePriceHistory->updated_by = Auth::id();
                $vehiclePriceHistory->status = $status;
                $vehiclePriceHistory->save();
            }
        }

        return redirect()->route('variant-prices.index')->with('success','Price Updated Successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
