<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvailableColour;
use App\Models\Varaint;
use App\Models\VehiclePriceHistory;
use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VariantPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('View Variant Price Info Page');
        $statuss = "Approved";
        $activeStocks = Vehicles::whereNull('gdn_id')
                                ->where('status', $statuss)
                                ->groupBy('varaints_id')
                                ->selectRaw('count(*) as total,id, varaints_id, int_colour, ex_colour, price')->get();

        $InactiveStocks =  Vehicles::whereNotNull('gdn_id')
                                ->where('status', $statuss)
                                ->groupBy('varaints_id')
                                ->selectRaw('count(*) as total,id, varaints_id, int_colour, ex_colour, price')
                                ->get();
        $activeStocks = $activeStocks->sortBy('price_status');
        $InactiveStocks = $InactiveStocks->sortBy('price_status');
        foreach ($activeStocks as $activeStock) {

            $activeStock->similar_vehicles_with_active_stock =  Vehicles::whereNull('gdn_id')->where('status', Vehicles::VEHICLE_STATUS_INCOMING)
                                                                    ->where('varaints_id', $activeStock->varaints_id)
                                                                    ->groupBy('int_colour','ex_colour')
                                                                    ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
                                                                    ->get();

            $similarVehicles = Vehicles::whereNull('gdn_id')
                ->where('varaints_id', $activeStock->varaints_id)
                ->where('int_colour', $activeStock->int_colour)
                ->where('ex_colour', $activeStock->ex_colour)
                ->pluck('id');

            $priceStatus = [];

            foreach ($similarVehicles as $similarVehicle) {
                $vehicle = Vehicles::find($similarVehicle);
                $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
                    ->where('int_colour', $vehicle->int_colour)
                    ->where('ext_colour', $vehicle->ex_colour)
                    ->first();
                if(!empty($availableColour)) {
                    $priceStatus[] = 'true';
                }else{
                    $priceStatus[] = 'false';
                }
            }
            if (array_unique($priceStatus) === array('true')) {
                $activeStock->active_vehicle_price_status = 1;
            }else{
                $activeStock->active_vehicle_price_status = 0;
            }
        }
        foreach ($InactiveStocks as $InactiveStock) {

                $InactiveStock->similar_vehicles_with_inactive_stock = Vehicles::whereNotNull('gdn_id')
                    ->where('status', Vehicles::VEHICLE_STATUS_INCOMING)
                    ->where('varaints_id', $InactiveStock->varaints_id)
                    ->groupBy('int_colour', 'ex_colour')
                    ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
                    ->get();

            $similarVehicles = Vehicles::whereNotNull('gdn_id')
                ->where('varaints_id', $InactiveStock->varaints_id)
                ->where('int_colour', $InactiveStock->int_colour)
                ->where('ex_colour', $InactiveStock->ex_colour)
                ->pluck('id');

            $priceStatus = [];

            foreach ($similarVehicles as $similarVehicle) {
                $vehicle = Vehicles::find($similarVehicle);
                $availableColour = AvailableColour::where('varaint_id', $vehicle->varaints_id)
                    ->where('int_colour', $vehicle->int_colour)
                    ->where('ext_colour', $vehicle->ex_colour)
                    ->first();
                if(!empty($availableColour)) {
                    $priceStatus[] = 'true';
                }else{
                    $priceStatus[] = 'false';
                }
            }
            if (array_unique($priceStatus) === array('true')) {
                $InactiveStock->inactive_vehicle_price_status = 1;
            }else{
                $InactiveStock->inactive_vehicle_price_status = 0;
            }
        }

        return view('variant-prices.index', compact('activeStocks','InactiveStocks'));
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
        (new UserActivityController)->createActivity('Edit Variant Price Page Open');
        $vehicle = Vehicles::find($id);

        $VariantPrices = AvailableColour::where('varaint_id', $vehicle->varaints_id)
            ->pluck('id');
        if($type == 1) {
         $similarVehicles =  Vehicles::whereNull('gdn_id')
                ->where('status', Vehicles::VEHICLE_STATUS_INCOMING)
                ->where('varaints_id', $vehicle->varaints_id)
                ->groupBy('int_colour','ex_colour')
                ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
                ->get();

            $vehicles =  $similarVehicles->sortBy('price_status');

            foreach ($vehicles as $eachVehicle) {
                $availableColour = AvailableColour::where('varaint_id', $eachVehicle->varaints_id)
                    ->where('int_colour', $eachVehicle->int_colour)
                    ->where('ext_colour', $eachVehicle->ex_colour)
                    ->first();

                $eachVehicle->old_price = 0;
                $eachVehicle->old_price_dated = "";
                $eachVehicle->updated_by = "";
                if(!empty($availableColour)) {
                    $eachVehicle->updated_by = $availableColour->user->name;
                    $priceHistories = VehiclePriceHistory::where('available_colour_id', $availableColour->id)
                        ->orderBy('id','DESC');
                    if($priceHistories->count() > 1 ) {
                        $latestPrice = $priceHistories->skip(1)->first();
                        $eachVehicle->old_price_dated = Carbon::parse($latestPrice->updated_at)->format('d/m/y, H:i:s');
                    }
                    $latestPriceHistory = $priceHistories->first();
                    if(!empty($latestPriceHistory)) {
                        $eachVehicle->old_price  =  $latestPriceHistory->old_price;
                    }
                }

            }
            $variantPriceHistories = VehiclePriceHistory::whereIn('available_colour_id', $VariantPrices)
                ->orderBy('id','DESC')->get();
        }else{
            $similarVehicles = Vehicles::whereNotNull('gdn_id')
                ->where('status', Vehicles::VEHICLE_STATUS_INCOMING)
                ->where('varaints_id', $vehicle->varaints_id)
                ->groupBy('int_colour', 'ex_colour')
                ->selectRaw('count(*) as count,id, varaints_id, int_colour, ex_colour, price')
                ->get();

            $vehicles =  $similarVehicles->sortBy('price_status');

            foreach ($vehicles as $eachVehicle) {
                $availableColour = AvailableColour::where('varaint_id', $eachVehicle->varaints_id)
                    ->where('int_colour', $eachVehicle->int_colour)
                    ->where('ext_colour', $eachVehicle->ex_colour)
                    ->first();

                $eachVehicle->old_price = 0;
                $eachVehicle->old_price_dated = " ";
                $eachVehicle->updated_by = " ";
                if(!empty($availableColour)) {
                    $eachVehicle->updated_by = $availableColour->user->name;
                    $priceHistories = VehiclePriceHistory::where('available_colour_id', $availableColour->id)
                        ->orderBy('id','DESC');

                    if($priceHistories->count() > 1 ) {
                        $latestPrice = $priceHistories->skip(1)->first();
                        $eachVehicle->old_price_dated = Carbon::parse($latestPrice->updated_at)->format('d/m/y, H:i:s');
                    }
                    $latestPriceHistory = $priceHistories->first();
                    if(!empty($latestPriceHistory)) {
                        $eachVehicle->old_price  =  $latestPriceHistory->old_price;
                    }
                }


            }

            $variantPriceHistories = VehiclePriceHistory::whereIn('available_colour_id', $VariantPrices)
                ->orderBy('id','DESC')->get();
       }

        return view('variant-prices.edit', compact('vehicle','variantPriceHistories','vehicles'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Update the Variant Price');
        $request->validate([
            'prices' => 'required'
        ]);

        $prices = $request->prices;
        $vehicles = $request->vehicle_ids;

        foreach ($prices as $key => $price ) {
            $vehicle = Vehicles::find($vehicles[$key]);

            $similarVehicles = Vehicles::where('varaints_id',$vehicle->varaints_id);
            $available_color = AvailableColour::where('varaint_id', $vehicle->varaints_id);
            if($vehicle->int_colour ) {
                $similarVehicles = $similarVehicles->where('int_colour',$vehicle->int_colour);
                $available_color = $available_color->where('int_colour', $vehicle->int_colour);
            }else{
                $similarVehicles = $similarVehicles->whereNull('int_colour');

            }
            if($vehicle->ex_colour){
                $similarVehicles = $similarVehicles->where('ex_colour', $vehicle->ex_colour);
                $available_color = $available_color->where('ext_colour', $vehicle->ex_colour);
            }else{
                $similarVehicles = $similarVehicles->whereNull('ex_colour');

            }
            $similarVehicles = $similarVehicles->get();

            if($price != $vehicle->price) {
                $available_color = $available_color->first();

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

            foreach ($similarVehicles as $vehicle) {
                if($price != $vehicle->price) {
                    $vehicle->price = $price;
                    $vehicle->save();
                }
            }
        }

        return redirect()->back()->with('success','Price Updated Successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
