<?php

namespace App\Http\Controllers;
use App\Models\Vehicles;
use App\Models\PurchasingOrder;
use App\Models\Varaint;
use App\Models\grn;
use App\Models\Gdn;
use App\Models\Document;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Vehicles::get();
        $varaint = Varaint::get();
        return view('vehicles.index', compact('data', 'varaint'));  
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
    public function updatevehiclesdata(Request $request)
    {
        $vehiclesId = $request->input('vehicles_id');
        $column = $request->input('column');
        $value = $request->input('value');
        if($column === "vin")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->vin = $value;
        $vehicle->save();
        }
        if($column === "price")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->price = $value;
        $vehicle->save();
        }
        if($column === "int_colour")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->int_colour = $value;
        $vehicle->save();
        }
        if($column === "ex_colour")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->ex_colour = $value;
        $vehicle->save();
        }
        if($column === "engine")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->engine = $value;
        $vehicle->save();
        }
        if($column === "remarks")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->remarks = $value;
        $vehicle->save();
        }
        if($column === "territory")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->territory = $value;
        $vehicle->save();
        }
        if($column === "documzinout")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->documzinout = $value;
        $vehicle->save();
        }
        if($column === "ppmmyyy")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->ppmmyyy = $value;
        $vehicle->save();
        }
        if ($column === "grn_date") {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $grnId = $vehicle->grn_id;
                if ($grnId) {
                    $grn = Grn::find($grnId);
                    if ($grn) {
                        $grn->date = $value;
                        $grn->save();
                    }
                } else {
                    $newGrn = new Grn();
                    $newGrn->date = $value;
                    $newGrn->save();
                    $vehicle->grn_id = $newGrn->id;
                    $vehicle->save();
                }
            }
        } 
        if ($column === "grn_number") {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $grnId = $vehicle->grn_id;
                if ($grnId) {
                    $grn = Grn::find($grnId);
                    if ($grn) {
                        $grn->grn_number = $value;
                        $grn->save();
                    }
                } else {
                    $newGrn = new Grn();
                    $newGrn->grn_number = $value;
                    $newGrn->save();
                    $vehicle->grn_id = $newGrn->id;
                    $vehicle->save();
                }
            }
        }
        if ($column === "gdn_date") {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $gdnId = $vehicle->gdn_id;
                if ($gdnId) {
                    $gdn = Gdn::find($gdnId);
                    if ($gdn) {
                        $gdn->date = $value;
                        $gdn->save();
                    }
                } else {
                    $newGdn = new Gdn();
                    $newGdn->date = $value;
                    $newGdn->save();
                    $vehicle->gdn_id = $newGdn->id;
                    $vehicle->save();
                }
            }
        }  
        if ($column === "gdn_number") {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $gdnId = $vehicle->gdn_id;
                if ($gdnId) {
                    $gdn = Gdn::find($gdnId);
                    if ($gdn) {
                        $gdn->gdn_number = $value;
                        $gdn->save();
                    }
                } else {
                    $newGdn = new Gdn();
                    $newGdn->gdn_number = $value;
                    $newGdn->save();
                    $vehicle->gdn_id = $newGdn->id;
                    $vehicle->save();
                }
            }
        }  
        if($column === "variants_name")
        {
            $variant = Varaint::where('name', $value)->first();
            if ($variant) {
                Vehicles::where('id', $vehiclesId)
                ->update(['varaints_id' => $variant->id]);
            }
        }
        if($column === "import_type")
        {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->import_type = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->import_type = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
        }
        if($column === "owership")
        {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->owership = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->owership = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
        }
        if($column === "document_with")
        {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->document_with = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->document_with = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
       
        }
        return response()->json(['message' => 'Vehicle data updated successfully']);
    }

    public function fatchvariantdetails(Request $request)
{
    $variantName = $request->input('value');
    // Fetch the updated values from the database based on the selected variant
    $result = DB::table('varaints')
    ->join('brands', 'varaints.brands_id', '=', 'brands.id')
    ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->where('varaints.name', $variantName)
    ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat','varaints.gearbox', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
    ->first();

    // Prepare the response data
    $responseData = [
        'varaints_detail' => $result->detail ?? null,
        'brand_name' => $result->brand_name ?? null,
        'model_line' => $result->model_line ?? null,
        'my' => $result->my ?? null,
        'upholestry' => $result->upholestry ?? null,
        'steering' => $result->steering ?? null,
        'fuel' => $result->fuel_type ?? null,
        'seat' => $result->seat ?? null,
        'gearbox' => $result->gearbox ?? null,
        'vehicles_id' => $request->input('vehicles_id'),
    ];

    return response()->json($responseData);
}
}
