<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Varaint;
use App\Models\VehiclePicture;
use App\Models\Vehicles;
use App\Models\MasterModelLines;
use App\Models\Inspection;
use App\Models\ColorCode;
use Illuminate\Http\Request;

class VehiclePicturesController extends Controller
{
    public function index()
    {
        $vehiclePictures = VehiclePicture::orderBy('id','DESC')->get();
        return view('vehicle_pictures.index',compact('vehiclePictures'));
    }
    public function create()
    {
        $vins = Vehicles::whereNotNull('vin')->get();
        return view('vehicle_pictures.create',compact('vins'));
    }
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
    public function pending(Request $request)
{
    if ($request->ajax()) {
        $status = $request->input('status');
        $data = Inspection::select([
                'inspection.id',
                'vehicles.id as vehicle_id', // Include the vehicle ID
                'vehicles.vin',
                'vehicles.inspection_status',
                DB::raw('GROUP_CONCAT(inspection.stage SEPARATOR ", ") as stages'),
                DB::raw('GROUP_CONCAT(DATE_FORMAT(inspection.created_at, "%d-%b-%Y") SEPARATOR ", ") as created_at_formatted'),
                'varaints.name as variant',
                'varaints.model_detail',
                'varaints.detail',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
                'purchasing_order.po_number',
                // 'grn.grn_number',
                'movement_grns.grn_number',
                DB::raw('GROUP_CONCAT(vehicle_pictures.vehicle_picture_link SEPARATOR ", ") as links'),
                'so.so_number',
                DB::raw('(SELECT GROUP_CONCAT(field) FROM vehicle_detail_approval_requests WHERE inspection_id = inspection.id) as changing_fields')
            ])
            ->leftJoin('vehicles', 'inspection.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            // ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
            ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('vehicle_pictures', function ($join) {
                $join->on('inspection.vehicle_id', '=', 'vehicle_pictures.vehicle_id')
                     ->whereRaw('inspection.stage = vehicle_pictures.category');
            });
            if ($status === 'Pending') {
                $data->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicle_pictures')
                    ->whereRaw('vehicle_pictures.vehicle_id = inspection.vehicle_id')
                    ->whereRaw('vehicle_pictures.category = inspection.stage');
            });
        }
        elseif ($status === 'Submitted') {
            $data->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicle_pictures')
                    ->whereRaw('vehicle_pictures.vehicle_id = inspection.vehicle_id')
                    ->whereRaw('vehicle_pictures.category = inspection.stage');
            });
        }
        $data = $data->groupBy('vehicles.id');
        return DataTables::of($data)->toJson();
    }

    return view('vehicle_pictures.pending');
}
public function saving(Request $request)
{
    $request->validate([
        'vehicleId' => 'required|integer',
        'links' => 'required|array',
        'links.*.stage' => 'required|string',
        'links.*.link' => 'nullable|string',
    ]);
    try {
        $vehicleId = $request->input('vehicleId');
        $links = $request->input('links');
        foreach ($links as $linkData) {
            $stage = $linkData['stage'];
            $link = $linkData['link'];
            $vehiclepictures = New VehiclePicture();
            $vehiclepictures->vehicle_id =$vehicleId;
            $vehiclepictures->vehicle_picture_link =$link;
            $vehiclepictures->category =$stage;
            $vehiclepictures->save();   
        }
        return response()->json(['message' => 'Links saved successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to save links'], 500);
    }
}
}
