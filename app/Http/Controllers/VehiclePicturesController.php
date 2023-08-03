<?php

namespace App\Http\Controllers;

use App\Models\Varaint;
use App\Models\VehiclePicture;
use App\Models\Vehicles;
use Illuminate\Http\Request;

class VehiclePicturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiclePictures = VehiclePicture::orderBy('id','DESC')->get();
        return view('vehicle_pictures.index',compact('vehiclePictures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vins = Vehicles::whereNotNull('vin')->get();
        return view('vehicle_pictures.create',compact('vins'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'vin' => 'required',
        ]);
        $counts = 0;
        $vins = $request->vin;
        foreach ($request->vin as $key => $vin) {
            $vehicle = Vehicles::where('vin', $vin)->first();
            if($request->category[$key] === "Modification")
            {
            $vehiclePicture = new VehiclePicture();
            $vehiclePicture->vehicle_id  = $vehicle->id;
            $vehiclePicture->vehicle_picture_link = $request->vehicle_picture_link[$key];
            $vehiclePicture->category = $request->category[$key];
            $vehiclePicture->save();
            }
            else{
                $existing = VehiclePicture::where('vehicle_id', $vehicle->id)->where('category', $request->category[$key])->first();
                if(!$existing)
                {
                $vehiclePicture = new VehiclePicture();
                $vehiclePicture->vehicle_id  = $vehicle->id;
                $vehiclePicture->vehicle_picture_link = $request->vehicle_picture_link[$key];
                $vehiclePicture->category = $request->category[$key];
                $vehiclePicture->save();
                if($request->category[$key] === "GRN")
                {
                    $vehicle = Vehicles::find($vehicle->id);
                    if ($vehicle) {
                        $currentDate = now();
                        $vehicle->inspection_date = $currentDate;
                        $vehicle->save();
                    }
                }
                if($request->category[$key] === "PDI")
                {
                    $vehicle = Vehicles::find($vehicle->id);
                    if ($vehicle) {
                        $currentDate = now();
                        $vehicle->pdi_date = $currentDate;
                        $vehicle->save();
                    }
                    
                }
                }
                else{
                $counts = 1;
                }
            }
        }
        if($counts > 0)
        {
        return redirect()->route('vehicle-pictures.index')->with('error', 'Items Already Existing that cannot save Other Picture Record Saved');
        }
        else
        return redirect()->route('vehicle-pictures.index')->with('success', 'Picture Record Saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehiclePicture = VehiclePicture::findOrFail($id);

        return view('vehicle_pictures.show',compact('vehiclePicture'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehiclePicture = VehiclePicture::findOrFail($id);

        $alreadyAddedVehicleIds = VehiclePicture::whereNot('id', $vehiclePicture->id)
                                                    ->pluck('vehicle_id');
        $vins = Vehicles::whereNotIn('id', $alreadyAddedVehicleIds)
                            ->get();

        return view('vehicle_pictures.edit',compact('vins','vehiclePicture'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vehiclePicture = VehiclePicture::findOrFail($id);
        $this->validate($request, [
            'vin' => 'required',
        ]);

        $vehiclePicture->vehicle_id  = $request->input('vin');
        $vehiclePicture->vehicle_picture_link = $request->input('vehicle_picture_link');
        $vehiclePicture->save();

        return redirect()->route('vehicle-pictures.index')->with('success','Vehicle picture updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehiclePicture = VehiclePicture::findOrFail($id);
        $vehiclePicture->delete();

        return response(true);
    }
    public function getVariantDetail(Request $request)
    {
        $vehicle = Vehicles::where('id', $request->id)->first();
        $variant = Varaint::find($vehicle->varaints_id);
        $data = $variant->detail;

        return response($data);
    }
    public function getVinForVehicle(Request $request) {

        $vehicleIds = VehiclePicture::pluck('vehicle_id');
        $data = Vehicles::select('id','vin')
                ->whereNotIn('id', $vehicleIds);

        if(!empty($request->filteredArray))
        {
          $data = $data->whereNotIn('id',$request->filteredArray);
        }
        $data = $data->get();
        return response()->json($data);
    }
}
