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
        $alreadyAddedVehicleIds = VehiclePicture::pluck('vehicle_id');
        $vins = Vehicles::whereNotIn('id', $alreadyAddedVehicleIds)
                            ->get();
        return view('vehicle_pictures.create',compact('vins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return $request->all();
        $this->validate($request, [
            'vins' => 'required',
        ]);

        $vins = $request->vins;
        foreach ($request->vins as $key => $vin) {
            $vehiclePicture = new VehiclePicture();
            $vehiclePicture->vehicle_id  = $vin;
            $vehiclePicture->GDN_link = $request->GDN_link[$key];
            $vehiclePicture->GRN_link = $request->GRN_link[$key];
            $vehiclePicture->modification_link = $request->modification_link[$key];
            $vehiclePicture->save();
        }

        return redirect()->route('vehicle-pictures.index')->with('success','Vehicle picture added successfully.');

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
        $vehiclePicture->GDN_link = $request->input('GDN_link');
        $vehiclePicture->GRN_link = $request->input('GRN_link');
        $vehiclePicture->modification_link = $request->input('modification_link');
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
