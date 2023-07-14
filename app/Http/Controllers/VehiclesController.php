<?php

namespace App\Http\Controllers;
use App\Models\ColorCode;
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
        $statuss = "Vendor Confirmed";
        $data = Vehicles::where('status', '!=', 'cancel')->where('payment_status', $statuss)->get();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();
        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
        ,'exteriorColours','interiorColours'));
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
    public function getVehicleDetails(Request $request) {
        $variant = Varaint::find($request->variant_id);
        $brand = $variant->brand->brand_name ?? '';
        $data['brand'] = $brand;
        $data['model_line'] = $variant->master_model_lines->model_line ?? '';
        $data['my'] = $variant->my ?? '';
        $data['model_detail'] = $variant->model_detail ?? '';
        $data['seat'] = $variant->seat ?? '';
        $data['fuel_type'] = $variant->fuel_type ?? '';
        $data['gearbox'] = $variant->gearbox ?? '';
        $data['steering'] = $variant->steering ?? '';
        $data['upholestry'] = $variant->upholestry ?? '';
        $data['detail'] = $variant->detail ?? '';

        return $data;

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
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicleId)
        {
            $vehicle = Vehicles::find($vehicleId);
            $id = $vehicleId;
            if($vehicle->inspection_date != $request->inspection_dates[$key])
            {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = 'inspection_date';
                $vehicleslog->old_value = $vehicle->inspection_date;
                $vehicleslog->new_value = $request->inspection_dates[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $vehicle->inspection_date = $request->inspection_dates[$key];

            }

            if($vehicle->varaints_id != $request->variants_ids[$key])
            {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = 'varaints_id';
                $vehicleslog->old_value = $vehicle->varaints_id;
                $vehicleslog->new_value = $request->variants_ids[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $vehicle->varaints_id = $request->variants_ids[$key];

            }
            if($vehicle->engine != $request->engines[$key])
            {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = 'engine';
                $vehicleslog->old_value = $vehicle->engine;
                $vehicleslog->new_value = $request->engines[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $vehicle->engine = $request->engines[$key];

            }
            if($vehicle->ex_colour != $request->exterior_colours[$key])
            {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = 'ex_colour';
                $vehicleslog->old_value = $vehicle->ex_colour;
                $vehicleslog->new_value = $request->exterior_colours[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $vehicle->ex_colour = $request->exterior_colours[$key];

            }
            if($vehicle->int_colour != $request->interior_colours[$key])
            {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = 'int_colour';
                $vehicleslog->old_value = $vehicle->int_colour;
                $vehicleslog->new_value = $request->interior_colours[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $vehicle->int_colour = $request->interior_colours[$key];
            }
            if($vehicle->ppmmyyy != $request->pymmyyyy[$key])
            {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = 'ppmmyyy';
                $vehicleslog->old_value = $vehicle->ppmmyyy;
                $vehicleslog->new_value = $request->pymmyyyy[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $vehicle->ppmmyyy = $request->pymmyyyy[$key];
            }
            $vehicle->save();
        }

        return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function updateso(Request $request)
    {
//        return $request->all();
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicleId)
        {
            $vehicle = Vehicles::find($vehicleId);

            $soId = $vehicle->so_id;
            if ($soId)
            {
                $so = So::find($soId);
                if($so->so_number != $request->so_numbers[$key])
                {
                    $solog = new Solog();
//                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
//                    $currentDateTime = Carbon::now($dubaiTimeZone);
                    $solog->time = $currentDateTime->toTimeString();
                    $solog->date = $currentDateTime->toDateString();
                    $solog->status = 'Update Sales Values';
                    $solog->so_id = $soId;
                    $solog->field = 'so_number';
                    $solog->old_value = $so->so_number;
                    $solog->new_value = $request->so_numbers[$key];
                    $solog->created_by = auth()->user()->id;
                    $solog->save();
                    $so->so_number = $request->so_numbers[$key];

                    $so->save();
                }

            } else
            {
//                if($request->so_numbers[$key]) {
//                    return redirect()->back()->with('error','So Number '.$request->so_numbers[$key].' is already added for another vehicle');
//
//                }
                // so should be unique then so number is updtaing existing so number.
                $existingSo = So::where('so_number', $request->so_numbers[$key])->first();
                if ($existingSo) {
                    // Update existing So
                    if($existingSo->so_number != $request->so_numbers[$key])
                    {
                        $solog = new Solog();
                        $solog->time = $currentDateTime->toTimeString();
                        $solog->date = $currentDateTime->toDateString();
                        $solog->status = 'Update Sales Values';
                        $solog->so_id = $existingSo->id;
                        $solog->field = 'so_number';
                        $solog->old_value = $existingSo->so_number;
                        $solog->new_value = $request->so_numbers[$key];
                        $solog->created_by = auth()->user()->id;
                        $solog->save();

                        $existingSo->so_number = $request->so_numbers[$key];
                        $so->save();
                    }
                    $existingSo->save();

                } else {
                    // Create new So
                    $so = new So();
                    $so->so_number = $request->so_numbers[$key];
                    $so->sales_person_id = $request->sales_persons[$key] ? $request->sales_persons[$key] : auth()->user()->id;
                    $so->save();
                    $soID = $so->id;
                    $vehicle->so_id = $soID;

                    // Save log in Solog

                    $colorlog = new Solog();
                    $colorlog->time = $currentDateTime->toTimeString();
                    $colorlog->date = $currentDateTime->toDateString();
                    $colorlog->status = 'New Created';
                    $colorlog->so_id = $soID;
                    $colorlog->field = 'so_number';
                    $colorlog->new_value = $so->so_number;
                    $colorlog->created_by = auth()->user()->id;
                    $colorlog->save();
                }
            }

            if ($request->reservation_start_dates[$key]) {
                $newReservationStartDate = $request->reservation_start_dates[$key];
                $newReservationEndDate = $request->reservation_end_dates[$key];

                $isStartDateChanged = $vehicle->reservation_start_date != $newReservationStartDate;
                $isEndDateChanged = $vehicle->reservation_end_date != $newReservationEndDate;

                if ($isStartDateChanged || $isEndDateChanged)
                {
                    if ($isStartDateChanged)
                    {
                        $vehicle->reservation_start_date = $newReservationStartDate;

                        $reservationStartDateLog = new Vehicleslog();

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

                    if ($isEndDateChanged)
                    {
                        $vehicle->reservation_end_date = $newReservationEndDate;

                        $reservationEndDateLog = new Vehicleslog();

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

            if($request->remarks[$key]){
                info("remarks");
                $remarksdata = new Remarks();

                $remarksdata->time = $currentDateTime->toTimeString();
                $remarksdata->date = $currentDateTime->toDateString();
                $remarksdata->vehicles_id = $vehicleId;
                $remarksdata->remarks = $request->remarks[$key];
                $remarksdata->created_by = auth()->user()->id;
                $remarksdata->department = "Sales";
                $remarksdata->created_at = $currentDateTime;
                $remarksdata->save();
            }
            // Save changes in the vehicles table
            $vehicle->save();

        }
        ///  Currenty not updating /////////////
        // Update payment_percentage if changed
//        if ($request->has('payment_percentage')) {
//            $newPaymentPercentage = $request->input('payment_percentage');
//            if ($vehicle->payment_percentage != $newPaymentPercentage) {
//                $vehicle->payment_percentage = $newPaymentPercentage;
//                $paymentPercentageLog = new Vehicleslog();
//                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
//                $currentDateTime = Carbon::now($dubaiTimeZone);
//                $paymentPercentageLog->time = $currentDateTime->toTimeString();
//                $paymentPercentageLog->date = $currentDateTime->toDateString();
//                $paymentPercentageLog->status = 'Update Vehicle Values';
//                $paymentPercentageLog->vehicles_id = $vehicle;
//                $paymentPercentageLog->field = 'payment_percentage';
//                $paymentPercentageLog->old_value = $vehicle->getOriginal('payment_percentage');
//                $paymentPercentageLog->new_value = $newPaymentPercentage;
//                $paymentPercentageLog->created_by = auth()->user()->id;
//                $paymentPercentageLog->save();
//            }
//        }
            // Update reservation_end_date if changed

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
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicle) {
            $vehicle = Vehicles::find($vehicle);
            $documents_id = $vehicle->documents_id;
            if ($documents_id) {
                $documents = Document::find($documents_id);

                if($documents->import_type != $request->import_types[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'import_type';
                    $documentlog->old_value = $documents->import_type;
                    $documentlog->new_value = $request->import_types[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->import_type = $request->import_types[$key];
                }
                if($documents->owership != $request->owerships[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'owership';
                    $documentlog->old_value = $documents->owership;
                    $documentlog->new_value = $request->owerships[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->owership = $request->owerships[$key];
                }
                if($documents->document_with != $request->documents_with[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'document_with';
                    $documentlog->old_value = $documents->document_with;
                    $documentlog->new_value = $request->documents_with[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->document_with = $request->documents_with[$key];
                }
                if($documents->bl_number != $request->bl_numbers[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'bl_number';
                    $documentlog->old_value = $documents->bl_number;
                    $documentlog->new_value = $request->bl_numbers[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->bl_number = $request->bl_numbers[$key];
                }
                $documents->save();

            }
            else {
                if(!empty($request->import_types[$key]) || !empty($request->owerships[$key]) ||
                    !empty($request->documents_with[$key]) || !empty($request->bl_numbers[$key]))
                {
                    $documents = new Document();
                    $documents->import_type = $request->import_types[$key];
                    $documents->owership = $request->owerships[$key];
                    $documents->document_with = $request->documents_with[$key];
                    $documents->bl_number = $request->bl_numbers[$key];
                    $documents->save();
                    $documents_id = $documents->id;

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

            }
        }
//         $vehicleId = $request->input('vehicle_id');

        return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function viewpictures($id)
    {
    $vehiclePictures = VehiclePicture::where('vehicle_id', $id)->get();
    return view('vehicle_pictures.show', compact('vehiclePictures'));
    }
    public function updatewarehouse(Request $request)
    {
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicleId) {
            $vehicle = Vehicles::find($vehicleId);
            $vehicle->conversion = $request->conversions[$key];

            if($vehicle->remarks != $request->warehouse_remarks[$key]) {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $vehicleId;
                $vehicleslog->field = 'remarks';
                $vehicleslog->old_value = $vehicle->remarks;
                $vehicleslog->new_value = $request->warehouse_remarks[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
                $vehicle->remarks = $request->warehouse_remarks[$key];

            }
            if($vehicle->conversion != $request->conversions[$key]) {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $vehicleId;
                $vehicleslog->field = 'conversion';
                $vehicleslog->old_value = $vehicle->conversion;
                $vehicleslog->new_value = $request->conversions[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
                $vehicle->conversion = $request->conversions[$key];

            }
            $vehicle->save();
        }

        return redirect()->back()->with('success', 'Details updated successfully.');
    }
    }
