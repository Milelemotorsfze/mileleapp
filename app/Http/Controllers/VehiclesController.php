<?php

namespace App\Http\Controllers;
use App\Models\Vehicles;
use App\Models\PurchasingOrder;
use App\Models\Varaint;
use App\Models\grn;
use App\Models\Gdn;
use App\Models\Document;
use App\Models\Documentlog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ModelHasRoles;
use App\Models\So;
use App\Models\Vehicleslog;
use App\Models\Solog;
use App\Models\Remarks;
use App\Models\VehiclePicture;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\DB;



class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Vehicles::where('status', '!=', 'cancel')->where('payment_status', '==', 'Payment Completed')->get();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'));  
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
    public function update(Request $request, string $id)
    {
        //
    }
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
        $oldValues = $vehicle->toArray();
        $variants_name = $request->input('variants_name');
        $variants_id = Varaint::where('name', $variants_name)->value('id');
        $vehicle->varaints_id = $variants_id;
        $vehicle->vin = $request->input('vin');
        $vehicle->engine = $request->input('engine');
        $vehicle->ex_colour = $request->input('ex_colour');
        $vehicle->int_colour = $request->input('int_colour');
        $vehicle->territory = $request->input('territory');
        $vehicle->inspection_date = $request->input('inspection');
        $vehicle->ppmmyyy = $request->input('ppmmyy');
        $changes = [];
        foreach ($oldValues as $field => $oldValue) {
            if ($field !== 'created_at' && $field !== 'updated_at') {
                $newValue = $vehicle->$field;
                if ($oldValue != $newValue) {
                    $changes[$field] = [
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ];
                }
            }
        }
        if (!empty($changes)) {
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            foreach ($changes as $field => $change) {
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update QC Values';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = $field;
            $vehicleslog->old_value = $change['old_value'];
            $vehicleslog->new_value = $change['new_value'];
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->save();
            }
        }
        return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function updateso(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicles::find($vehicleId);
        $soId = $vehicle->so_id;
        if ($soId) {
            // Update existing So
            $so = So::find($soId);
            $oldValues = $so->toArray();
            $so->so_number = $request->input('so_number');
    
            $changes = [];
            foreach ($oldValues as $field => $oldValue) {
                if ($field !== 'created_at' && $field !== 'updated_at') {
                    $newValue = $so->$field;
                    if ($oldValue != $newValue) {
                        $changes[$field] = [
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                        ];
                    }
                }
            }
            if (!empty($changes)) {
                $so->save();
                // Save changes in Solog
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                foreach ($changes as $field => $change) {
                    $solog = new Solog();
                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                    $currentDateTime = Carbon::now($dubaiTimeZone);
                    $solog->time = $currentDateTime->toTimeString();
                    $solog->date = $currentDateTime->toDateString();
                    $solog->status = 'Update Sales Values';
                    $solog->so_id = $soId;
                    $solog->field = $field;
                    $solog->old_value = $change['old_value'];
                    $solog->new_value = $change['new_value'];
                    $solog->created_by = auth()->user()->id;
                    $solog->save();
                }
            }
        } else {
            $existingSo = So::where('so_number', $request->input('so_number'))->first();
            if ($existingSo) {
                // Update existing So
                $oldValues = $existingSo->toArray();
                $existingSo->save();
    
                $changes = [];
                foreach ($oldValues as $field => $oldValue) {
                    if ($field !== 'created_at' && $field !== 'updated_at') {
                        $newValue = $existingSo->$field;
                        if ($oldValue != $newValue) {
                            $changes[$field] = [
                                'old_value' => $oldValue,
                                'new_value' => $newValue,
                            ];
                        }
                    }
                }
                if (!empty($changes)) {
                    $existingSo->save();
                    $soID = $existingSo->id;
                    // Save changes in Solog
                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                    $currentDateTime = Carbon::now($dubaiTimeZone);
                    foreach ($changes as $field => $change) {
                        $solog = new Solog();
                        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        $currentDateTime = Carbon::now($dubaiTimeZone);
                        $solog->time = $currentDateTime->toTimeString();
                        $solog->date = $currentDateTime->toDateString();
                        $solog->status = 'Update Sales Values';
                        $solog->so_id = $soID;
                        $solog->field = $field;
                        $solog->old_value = $change['old_value'];
                        $solog->new_value = $change['new_value'];
                        $solog->created_by = auth()->user()->id;
                        $solog->save();
                    }
                }
            } else {
                // Create new So
                $so = new So();
                $so->so_number = $request->input('so_number');
                $so->sales_person_id = $request->has('sales_person') ? $request->input('sales_person') : auth()->user()->id;
                $so->save();
                $soID = $so->id;
    
                // Save log in Solog
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
    
                $colorlog = new Solog();
                $colorlog->time = $currentDateTime->toTimeString();
                $colorlog->date = $currentDateTime->toDateString();
                $colorlog->status = 'New Created';
                $colorlog->so_id = $soID;
                $colorlog->created_by = auth()->user()->id;
                $colorlog->save();
            }
        }
        // Update payment_percentage if changed
        if ($request->has('payment_percentage')) {
            $newPaymentPercentage = $request->input('payment_percentage');
            if ($vehicle->payment_percentage != $newPaymentPercentage) {
                $vehicle->payment_percentage = $newPaymentPercentage;
                $paymentPercentageLog = new Vehicleslog();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $paymentPercentageLog->time = $currentDateTime->toTimeString();
                $paymentPercentageLog->date = $currentDateTime->toDateString();
                $paymentPercentageLog->status = 'Update Vehicle Values';
                $paymentPercentageLog->vehicles_id = $vehicleId;
                $paymentPercentageLog->field = 'payment_percentage';
                $paymentPercentageLog->old_value = $vehicle->getOriginal('payment_percentage');
                $paymentPercentageLog->new_value = $newPaymentPercentage;
                $paymentPercentageLog->created_by = auth()->user()->id;
                $paymentPercentageLog->save();
            }
        }
            // Update reservation_end_date if changed
if ($request->has('reservation_start_date')) {
    $newReservationStartDate = $request->input('reservation_start_date');
    $newReservationEndDate = $request->input('reservation_end_date');
    
    $isStartDateChanged = $vehicle->reservation_start_date != $newReservationStartDate;
    $isEndDateChanged = $vehicle->reservation_end_date != $newReservationEndDate;

    if ($isStartDateChanged || $isEndDateChanged) {
        if ($isStartDateChanged) {
            $vehicle->reservation_start_date = $newReservationStartDate;
            
            $reservationStartDateLog = new Vehicleslog();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $reservationStartDateLog->time = $currentDateTime->toTimeString();
            $reservationStartDateLog->date = $currentDateTime->toDateString();
            $reservationStartDateLog->status = 'Update Vehicle Values';
            $reservationStartDateLog->vehicles_id = $vehicleId;
            $reservationStartDateLog->field = 'reservation_start_date';
            $reservationStartDateLog->old_value = $vehicle->getOriginal('reservation_start_date');
            $reservationStartDateLog->new_value = $newReservationStartDate;
            $reservationStartDateLog->created_by = auth()->user()->id;
            $reservationStartDateLog->save();
        }

        if ($isEndDateChanged) {
            $vehicle->reservation_end_date = $newReservationEndDate;

            $reservationEndDateLog = new Vehicleslog();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $reservationEndDateLog->time = $currentDateTime->toTimeString();
            $reservationEndDateLog->date = $currentDateTime->toDateString();
            $reservationEndDateLog->status = 'Update Vehicle Values';
            $reservationEndDateLog->vehicles_id = $vehicleId;
            $reservationEndDateLog->field = 'reservation_end_date';
            $reservationEndDateLog->old_value = $vehicle->getOriginal('reservation_end_date');
            $reservationEndDateLog->new_value = $newReservationEndDate;
            $reservationEndDateLog->created_by = auth()->user()->id;
            $reservationEndDateLog->save();
        }
    }
}
        if($request->has('remarks')){
        $remarksdata = new Remarks();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $remarksdata->time = $currentDateTime->toTimeString();
        $remarksdata->date = $currentDateTime->toDateString();
        $remarksdata->vehicles_id = $vehicleId;
        $remarksdata->remarks = $request->input('remarks');
        $remarksdata->created_by = auth()->user()->id;
        $remarksdata->department = "Sales";
        $remarksdata->created_at = $currentDateTime;
        $remarksdata->save();
        }
        // Save changes in the vehicles table
        $vehicle->save();
        return redirect()->back()->with('success', 'Sales details updated successfully.');
    }    
    public function deletes($id)
    {
    $vehicle = Vehicles::find($id); 
    if ($vehicle->grn_id === null) {
        $vehicle->status = 'cancel';
        $vehicle->save();
        return redirect()->back()->with('success', 'Vehicle status updated to "cancel" successfully.');
    } else {
        return redirect()->back()->with('error', 'Vehicle has already been delivered and cannot be canceled.');
    }
    }
    public function viewLogDetails($id)
    {
        $vehicle = Vehicles::find($id);
        $documentsLog = Documentlog::where('documents_id', $vehicle->documents_id);
        $soLog = Solog::where('so_id', $vehicle->so_id);
        $vehiclesLog = Vehicleslog::where('vehicles_id', $vehicle->id);
        $mergedLogs = $documentsLog->union($soLog)->union($vehiclesLog)->orderBy('created_at')->get();
        $previousId = Vehicles::where('id', '<', $id)->max('id');
        $nextId = Vehicles::where('id', '>', $id)->min('id');
        return view('vehicles.vehicleslog', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ], compact('mergedLogs', 'vehicle'));
    }
    public function  viewremarks($id)
    {
        $remarks = Remarks::where('vehicles_id', $id)->get();
        $previousId = Vehicles::where('id', '<', $id)->max('id');
        $nextId = Vehicles::where('id', '>', $id)->min('id');
        return view('vehicles.viewremarks', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ], compact('remarks'));
    }
    public function updatelogistics(Request $request)
    {
         $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicles::find($vehicleId);
        $documents_id = $vehicle->documents_id;
		if ($documents_id) {
            $documents = Document::find($documents_id);
            $oldValues = $documents->toArray();
            $documents->import_type = $request->input('import_type');
			$documents->owership = $request->input('owership');
			$documents->document_with = $request->input('document_with');
			$documents->bl_status = $request->input('bl_status');
            $changes = [];
            foreach ($oldValues as $field => $oldValue) {
                if ($field !== 'created_at' && $field !== 'updated_at') {
                    $newValue = $documents->$field;
                    if ($oldValue != $newValue) {
                        $changes[$field] = [
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                        ];
                    }
                }
            }
            if (!empty($changes)) {
                $documents->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                foreach ($changes as $field => $change) {
                    $documentlog = new Documentlog();
                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                    $currentDateTime = Carbon::now($dubaiTimeZone);
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = $field;
                    $documentlog->old_value = $change['old_value'];
                    $documentlog->new_value = $change['new_value'];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();
                }
            }
        }
		else {
            $documents = new Document();
            $documents->import_type = $request->input('import_type');
			$documents->owership = $request->input('owership');
			$documents->document_with = $request->input('document_with');
			$documents->document_with = $request->input('document_with');
			$documents->bl_status = $request->input('bl_status');
            $documents->save();
            $documents_id = $documents->id;
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
               $documentlog = new Documentlog();
                $documentlog->time = $currentDateTime->toTimeString();
                $documentlog->date = $currentDateTime->toDateString();
                $documentlog->status = 'New Created';
                $documentlog->documents_id = $documents_id;
                $documentlog->created_by = auth()->user()->id;
                $documentlog->save();
             $vehicle->documents_id = $documents_id;
             $vehicle->save();
            }
        return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function viewpictures($id)
    {
    $vehiclePictures = VehiclePicture::where('vehicle_id', $id)->get();
    return view('vehicle_pictures.show', compact('vehiclePictures'));
    }
    public function updatewarehouse(Request $request)
    {
        $id = $request->input('vehicle_id');
        $vehicle = Vehicles::find($id);
        $oldValues = $vehicle->toArray();
        $vehicle->remarks = $request->input('remarks');
        $vehicle->conversion = $request->input('conversion');
        $changes = [];
        foreach ($oldValues as $field => $oldValue) {
            if ($field !== 'created_at' && $field !== 'updated_at') {
                $newValue = $vehicle->$field;
                if ($oldValue != $newValue) {
                    $changes[$field] = [
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                    ];
                }
            }
        }
        if (!empty($changes)) {
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            foreach ($changes as $field => $change) {
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update QC Values';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = $field;
            $vehicleslog->old_value = $change['old_value'];
            $vehicleslog->new_value = $change['new_value'];
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->save();
            }
        }
        return redirect()->back()->with('success', 'Details updated successfully.');
    }
    }
