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
use App\Models\User;
use App\Models\ModelHasRoles;
use App\Models\So;
use Illuminate\Support\Facades\DB;



class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Vehicles::where('status', '!=', 'cancel')->get();
        $varaint = Varaint::get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        return view('vehicles.index', compact('data', 'varaint', 'sales'));  
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
    $result = DB::table('varaints')
    ->join('brands', 'varaints.brands_id', '=', 'brands.id')
    ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->where('varaints.name', $variantName)
    ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat','varaints.gearbox', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
    ->first();
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
    public function updatedata(Request $request)
    {
        $id = $request->input('vehicle_id');
        $vehicle = Vehicles::find($id);
        $variants_name = $request->input('variants_name');
        $variants_id = Varaint::where('name', $variants_name)->value('id');
        $vehicle->varaints_id = $variants_id;
        $vehicle->vin = $request->input('vin');
        $vehicle->engine = $request->input('engine');
        $vehicle->ex_colour = $request->input('ex_colour');
        $vehicle->int_colour = $request->input('int_colour');
        $vehicle->territory = $request->input('territory');
        $vehicle->ppmmyyy = $request->input('ppmmyy');
        $vehicle->remarks = $request->input('remarks');
        $vehicle->save();
        return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function updateso(Request $request)
    {
    $vehicleId = $request->input('vehicle_id');
    $vehicle = Vehicles::find($vehicleId);
    $soId = $vehicle->so_id;
    if ($soId) {
        $so = So::find($soId);
        $so->so_number = $request->input('so_number');
        $so->so_date = $request->input('so_date');
        $so->sales_person_id = $request->input('sales_person');
        $so->payment_percentage = $request->input('payment_percentage');
        $so->save();
    } else {
        $so = new So();
        $so->so_number = $request->input('so_number');
        $so->so_date = $request->input('so_date');
        $so->sales_person_id = $request->input('sales_person');
        $so->payment_percentage = $request->input('payment_percentage');
        $so->save();
        $vehicle->so_id = $so->id;
        $vehicle->save();
    }
    return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function deletes($id)
    {
    $vehicle = Vehicles::find($id); // Assuming you have a "Vehicle" model
    
    if ($vehicle->grn_id === null) {
        $vehicle->status = 'cancel';
        $vehicle->save();
        // You can also use $vehicle->update(['status' => 'cancel']);
        
        return redirect()->back()->with('success', 'Vehicle status updated to "cancel" successfully.');
    } else {
        return redirect()->back()->with('error', 'Vehicle has already been delivered and cannot be canceled.');
    }
    }
    public function viewLogDetails($id)
    {
        $lastIdDetails = Vehicles::find($id);
        $previousId = Vehicles::where('id', '<', $id)->max('id');
        $nextId = Vehicles::where('id', '>', $id)->min('id');
        return view('vehicles.vehicleslog', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ]);
    }
    }
