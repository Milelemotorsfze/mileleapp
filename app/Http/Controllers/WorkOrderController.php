<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WOVehicles;
use App\Models\WOVehicleAddons;
use App\Models\Customer;
use App\Models\Clients;
use App\Models\Vehicles;
use App\Models\User;
use App\Models\AddonDetails;
use App\Models\Masters\MasterAirlines;
use App\Models\Masters\MasterCharges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Http\Controllers\UserActivityController;
use App\Http\Requests\StoreWorkOrderRequest;
use Illuminate\Support\Facades\Auth;
class WorkOrderController extends Controller
{
    public function workOrderCreate($type) {
        // $type = 'export_exw';
        // $customers = Customer::orderBy('name','ASC')->get();
        // $clients = Clients::orderBy('name','ASC')->get();
        (new UserActivityController)->createActivity('Open '.$type.' work order create page');

        $dpCustomers = Customer::select(DB::raw('name as customer_name'), DB::raw('NULL as customer_email'), DB::raw('NULL as customer_company_number'), DB::raw('address as customer_address'))->distinct();
        $clients = Clients::select(DB::raw('name as customer_name'), DB::raw('email as customer_email'),DB::raw('phone as customer_company_number'), DB::raw('NULL as customer_address'))->distinct();
        // $workOrders = WorkOrder::select('customer_name', 'customer_email', 'customer_company_number', 'customer_address')->distinct();
        
        $customers = $dpCustomers->union($clients)->get();
        // ->union($workOrders)
        $customers = $customers->unique('customer_name');
       // Combine the queries ensuring each select has the same number of columns
        // Select and transform data from the Customer table
        // $dpCustomers = Customer::select(
        //     DB::raw('name as customer_name'), 
        //     DB::raw('NULL as customer_email'), 
        //     DB::raw('NULL as customer_company_number'), 
        //     DB::raw('address as customer_address'),
        //     DB::raw('(IF(address IS NOT NULL, 1, 0)) as score')
        // )->distinct();

        // // Select and transform data from the Clients table
        // $clients = Clients::select(
        //     DB::raw('name as customer_name'), 
        //     DB::raw('email as customer_email'), 
        //     DB::raw('phone as customer_company_number'), 
        //     DB::raw('NULL as customer_address'),
        //     DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score')
        // )->distinct();

        // // Select data from the WorkOrder table
        // $workOrders = WorkOrder::select(
        //     'customer_name', 
        //     'customer_email', 
        //     'customer_company_number', 
        //     'customer_address',
        //     DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score')
        // )->distinct();

        // // Combine the results
        // $combinedResults = $dpCustomers->union($clients)->union($workOrders)->get();

        // // Sort by score in descending order, then by customer_name in ascending order
        // $customers = $combinedResults->sortByDesc('score')->unique('customer_name')->values()->sortBy('customer_name');

        // // Convert collection to array or use directly if needed
        // $customersArray = $customers->toArray();

        // // Select distinct customer names with the highest scores
        // $uniqueCustomers = new Collection();
        // $customers->each(function ($item) use ($uniqueCustomers) {
        //     if (!$uniqueCustomers->contains('customer_name', $item->customer_name)) {
        //         $uniqueCustomers->push($item);
        //     }
        // });
        // dd($customers);
        // ->union($workOrders)
        $users = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->get();
        // $accSpaKits = AddonDetails::select('addon_code')->distinct();
        
        $airlines = MasterAirlines::orderBy('name','ASC')->get();
        $vins = Vehicles::orderBy('vin','ASC')->whereNotNull('vin')->with('variant.master_model_lines.brand','interior','exterior','warehouseLocation','document')->get()->unique('vin');
        return view('work_order.export_exw.create',compact('type','customers','airlines','vins','users'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index($type)
    {
        $datas = WorkOrder::where('type',$type)->latest()->get();
        return view('work_order.export_exw.index',compact('type','datas'));
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
    // public function store(StoreWorkOrderRequest $request)
    // {
    //     // dd($request->all());
    //     // DB::beginTransaction();

    //     // try {
    //         $authId = Auth::id();
    //         // Retrieve validated input data
    //         $validated = $request->validated();
    //         $input = $request->all();
    //         $input['customer_company_number'] = $request->customer_company_number['full'];
    //         $input['customer_representative_contact'] = $request->customer_representative_contact['full'];
    //         $input['freight_agent_contact_number'] = $request->freight_agent_contact_number['full'];
    //         if(isset($request->transporting_driver_contact_number['full'])) {
    //             $input['transporting_driver_contact_number'] = $request->transporting_driver_contact_number['full'];  
    //         }
    //         $input['customer_name'] = $request->new_customer_name;
    //         $input['created_by'] = $authId;
    //         if($request->brn_file) {
    //             $brnFileName = auth()->id() . '_' . time() . '.'. $request->brn_file->extension();
    //             $type = $request->brn_file->getClientMimeType();
    //             $size = $request->brn_file->getSize();
    //             $request->brn_file->move(public_path('wo/brn_file'), $brnFileName);
    //             $input['brn_file'] = $brnFileName;
    //         }
    //         if($request->signed_pfi) {
    //             $signedPdfFileName = auth()->id() . '_' . time() . '.'. $request->signed_pfi->extension();
    //             $type = $request->signed_pfi->getClientMimeType();
    //             $size = $request->signed_pfi->getSize();
    //             $request->signed_pfi->move(public_path('wo/signed_pfi'), $signedPdfFileName);
    //             $input['signed_pfi'] = $signedPdfFileName;
    //         }
    //         if($request->signed_contract) {
    //             $signedContractFileName = auth()->id() . '_' . time() . '.'. $request->signed_contract->extension();
    //             $type = $request->signed_contract->getClientMimeType();
    //             $size = $request->signed_contract->getSize();
    //             $request->signed_contract->move(public_path('wo/signed_contract'), $signedContractFileName);
    //             $input['signed_contract'] = $signedContractFileName;
    //         }
    //         if($request->payment_receipts) {
    //             $paymentReceiptsFileName = auth()->id() . '_' . time() . '.'. $request->payment_receipts->extension();
    //             $type = $request->payment_receipts->getClientMimeType();
    //             $size = $request->payment_receipts->getSize();
    //             $request->payment_receipts->move(public_path('wo/payment_receipts'), $paymentReceiptsFileName);
    //             $input['payment_receipts'] = $paymentReceiptsFileName;
    //         }
    //         if($request->noc) {
    //             $nocFileName = auth()->id() . '_' . time() . '.'. $request->noc->extension();
    //             $type = $request->noc->getClientMimeType();
    //             $size = $request->noc->getSize();
    //             $request->noc->move(public_path('wo/noc'), $nocFileName);
    //             $input['noc'] = $nocFileName;
    //         }
    //         if($request->enduser_trade_license) {
    //             $enduserTradeLicenseFileName = auth()->id() . '_' . time() . '.'. $request->enduser_trade_license->extension();
    //             $type = $request->enduser_trade_license->getClientMimeType();
    //             $size = $request->enduser_trade_license->getSize();
    //             $request->enduser_trade_license->move(public_path('wo/enduser_trade_license'), $enduserTradeLicenseFileName);
    //             $input['enduser_trade_license'] = $enduserTradeLicenseFileName;
    //         }
    //         if($request->enduser_passport) {
    //             $enduserPassportFileName = auth()->id() . '_' . time() . '.'. $request->enduser_passport->extension();
    //             $type = $request->enduser_passport->getClientMimeType();
    //             $size = $request->enduser_passport->getSize();
    //             $request->enduser_passport->move(public_path('wo/enduser_passport'), $enduserPassportFileName);
    //             $input['enduser_passport'] = $enduserPassportFileName;
    //         }
    //         if($request->enduser_contract) {
    //             $enduserContractFileName = auth()->id() . '_' . time() . '.'. $request->enduser_contract->extension();
    //             $type = $request->enduser_contract->getClientMimeType();
    //             $size = $request->enduser_contract->getSize();
    //             $request->enduser_contract->move(public_path('wo/enduser_contract'), $enduserContractFileName);
    //             $input['enduser_contract'] = $enduserContractFileName;
    //         }
    //         if($request->vehicle_handover_person_id) {
    //             $vehicleHandoverPersonIdFileName = auth()->id() . '_' . time() . '.'. $request->vehicle_handover_person_id->extension();
    //             $type = $request->vehicle_handover_person_id->getClientMimeType();
    //             $size = $request->vehicle_handover_person_id->getSize();
    //             $request->vehicle_handover_person_id->move(public_path('wo/vehicle_handover_person_id'), $vehicleHandoverPersonIdFileName);
    //             $input['vehicle_handover_person_id'] = $vehicleHandoverPersonIdFileName;
    //         }
    //         $workOrder = WorkOrder::create($input);
    //         // Create the WorkOrder record
    //         // $workOrder = WorkOrder::create([
    //         //     'name' => $validated['name'],
    //         //     'email' => $validated['email'],
    //         //     'contact_number' => $validated['contact_number'],
    //         //     // Add other fields as needed
    //         // ]);

    //         // Commit the transaction
    //     //     DB::commit();

    //     //     return response()->json(['message' => 'Work Order created successfully!', 'Work Order' => $workOrder], 201);
    //     // } catch (\Exception $e) {
    //     //     // Rollback the transaction
    //     //     DB::rollBack();

    //     //     // Log the error for debugging
    //     //     Log::error('Error creating Work Order: ' . $e->getMessage());

    //     //     return response()->json(['error' => 'Failed to create Work Order. Please try again.'], 500);
    //     // }
    // }
    public function store(StoreWorkOrderRequest $request)
    { 
        // dd($request->vehicle->addons[]);
        DB::beginTransaction();

        try {
            $authId = Auth::id();
            // Retrieve validated input data
            $validated = $request->validated();
            $input = $request->all();

            // Ensure these fields are properly converted to strings
            $input['customer_company_number'] = $request->customer_company_number['full'] ?? null;
            $input['customer_representative_contact'] = $request->customer_representative_contact['full'] ?? null;
            $input['freight_agent_contact_number'] = $request->freight_agent_contact_number['full'] ?? null;
            $input['transporting_driver_contact_number'] = $request->transporting_driver_contact_number['full'] ?? null;
            $input['customer_name'] = $request->customer_name ?? $request->new_customer_name;
            $input['created_by'] = $authId;
            $input['so_total_amount'] = $request->so_total_amount ?? 0.00;
            $input['amount_received'] = $request->amount_received ?? 0.00;
            $input['balance_amount'] = $request->balance_amount ?? 0.00;
            // Handle file uploads
            if ($request->hasFile('brn_file')) {
                $brnFileName = $this->handleFileUpload($request->file('brn_file'), 'wo/brn_file');
                $input['brn_file'] = $brnFileName;
            }

            if ($request->hasFile('signed_pfi')) {
                $signedPdfFileName = $this->handleFileUpload($request->file('signed_pfi'), 'wo/signed_pfi');
                $input['signed_pfi'] = $signedPdfFileName;
            }

            if ($request->hasFile('signed_contract')) {
                $signedContractFileName = $this->handleFileUpload($request->file('signed_contract'), 'wo/signed_contract');
                $input['signed_contract'] = $signedContractFileName;
            }

            if ($request->hasFile('payment_receipts')) {
                $paymentReceiptsFileName = $this->handleFileUpload($request->file('payment_receipts'), 'wo/payment_receipts');
                $input['payment_receipts'] = $paymentReceiptsFileName;
            }

            if ($request->hasFile('noc')) {
                $nocFileName = $this->handleFileUpload($request->file('noc'), 'wo/noc');
                $input['noc'] = $nocFileName;
            }

            if ($request->hasFile('enduser_trade_license')) {
                $enduserTradeLicenseFileName = $this->handleFileUpload($request->file('enduser_trade_license'), 'wo/enduser_trade_license');
                $input['enduser_trade_license'] = $enduserTradeLicenseFileName;
            }

            if ($request->hasFile('enduser_passport')) {
                $enduserPassportFileName = $this->handleFileUpload($request->file('enduser_passport'), 'wo/enduser_passport');
                $input['enduser_passport'] = $enduserPassportFileName;
            }

            if ($request->hasFile('enduser_contract')) {
                $enduserContractFileName = $this->handleFileUpload($request->file('enduser_contract'), 'wo/enduser_contract');
                $input['enduser_contract'] = $enduserContractFileName;
            }

            if ($request->hasFile('vehicle_handover_person_id')) {
                $vehicleHandoverPersonIdFileName = $this->handleFileUpload($request->file('vehicle_handover_person_id'), 'wo/vehicle_handover_person_id');
                $input['vehicle_handover_person_id'] = $vehicleHandoverPersonIdFileName;
            }

            // Create the WorkOrder record
            $workOrder = WorkOrder::create($input);
            if (isset($request->vehicle)) {
                if (count($request->vehicle) > 0) {
                    foreach ($request->vehicle as $key => $vehicleData) {
                        $createWOVehicles = [];
                        $createWOVehicles['work_order_id'] = $workOrder->id;
            
                        $createWOVehicles['vin'] = $vehicleData['vin'] ?? null;
                        $createWOVehicles['brand'] = $vehicleData['brand'] ?? null;
                        $createWOVehicles['variant'] = $vehicleData['variant'] ?? null;
                        $createWOVehicles['engine'] = $vehicleData['engine'] ?? null;
                        $createWOVehicles['model_description'] = $vehicleData['model_description'] ?? null;
                        $createWOVehicles['model_year'] = $vehicleData['model_year'] ?? null;
                        $createWOVehicles['model_year_to_mention_on_documents'] = $vehicleData['model_year_to_mention_on_documents'] ?? null;
                        $createWOVehicles['steering'] = $vehicleData['steering'] ?? null;
                        $createWOVehicles['exterior_colour'] = $vehicleData['exterior_colour'] ?? null;
                        $createWOVehicles['interior_colour'] = $vehicleData['interior_colour'] ?? null;
                        $createWOVehicles['warehouse'] = $vehicleData['warehouse'] ?? null;
                        $createWOVehicles['territory'] = $vehicleData['territory'] ?? null;
                        $createWOVehicles['preferred_destination'] = $vehicleData['preferred_destination'] ?? null;
                        $createWOVehicles['import_document_type'] = $vehicleData['import_document_type'] ?? null;
                        $createWOVehicles['ownership_name'] = $vehicleData['ownership_name'] ?? null;
                        $createWOVehicles['modification_or_jobs_to_perform_per_vin'] = $vehicleData['modification_or_jobs_to_perform_per_vin'] ?? null;
                        $createWOVehicles['certification_per_vin'] = $vehicleData['certification_per_vin'] ?? null;
                        $createWOVehicles['special_request_or_remarks'] = $vehicleData['special_request_or_remarks'] ?? null;
                        $createWOVehicles['shipment'] = $vehicleData['shipment'] ?? null;
                        $createWOVehicles['created_by'] = $authId;
            
                        $woVehicles = WOVehicles::create($createWOVehicles);
                        if (isset($vehicleData['addons'])) {
                            if (count($vehicleData['addons']) > 0) {
                                foreach ($vehicleData['addons'] as $key => $addonData) {
                                    $createWOVehiclesAddons = [];
                                    $createWOVehiclesAddons['w_o_vehicle_id'] = $woVehicles->id;
                        
                                    // $createWOVehiclesAddons['addon_reference_id'] = $addonData['vin'] ?? null;
                                    // $createWOVehiclesAddons['addon_reference_type'] = $addonData['brand'] ?? null;
                                    $createWOVehiclesAddons['addon_code'] = $addonData['addon_code'] ?? null;
                                    // $createWOVehiclesAddons['addon_name'] = $addonData['addon_name'] ?? null;
                                    // $createWOVehiclesAddons['addon_name_description'] = $addonData['addon_name_description'] ?? null;
                                    $createWOVehiclesAddons['addon_quantity'] = $addonData['quantity'] ?? null;
                                    $createWOVehiclesAddons['addon_description'] = $addonData['description'] ?? null;                                  
                                    $createWOVehiclesAddons['created_by'] = $authId;
                        
                                    $WOVehicleAddons = WOVehicleAddons::create($createWOVehiclesAddons);
                                }
                            }
                        }       
                    }
                }
            }            
            // Commit the transaction
            DB::commit();
            return redirect()->route('work-order-create.index',$request->type)->with('success','Work Order created successfully!');
            // return response()->json(['message' => 'Work Order created successfully!', 'Work Order' => $workOrder], 201);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error creating Work Order: ' . $e->getMessage());
        dd($e);
            return response()->json(['error' => 'Failed to create Work Order. Please try again.'], 500);
        }
    }

    /**
     * Handle file upload.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string
     */
    private function handleFileUpload($file, $path)
    {
        $fileName = auth()->id() . '_' . time() . '.' . $file->extension();
        $file->move(public_path($path), $fileName);
        return $fileName;
    }
    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
        // $type = $workOrder->type;
        // $previous = WorkOrder::where('type',$type)->where('id', '<', $workOrder->id)->max('id');
        // $next = WorkOrder::where('type',$type)->where('id', '>', $workOrder->id)->min('id');
        // return view('work_order.export_exw.show',compact('type','workOrder','previous','next'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        $errorMsg ="This page will coming very soon !";
        return view('hrm.notaccess',compact('errorMsg'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        //
    }
    public function fetchAddons(Request $request)
    {
        // $vins = $request->input('vins');
        // if (isset($vins) && count($vins) > 0) {
        $kit = AddonDetails::select('addon_details.id','addon_details.addon_code',DB::raw("CONCAT(addons.name, 
                IF(addon_descriptions.description IS NOT NULL AND addon_descriptions.description != '', CONCAT(' - ', addon_descriptions.description), '')) as addon_name
                "),DB::raw("'App\\Models\\AddonDetails' as reference_type"))
            ->join('addon_descriptions', 'addon_details.description', '=', 'addon_descriptions.id')
            ->join('addons', 'addon_descriptions.addon_id', '=', 'addons.id')
            ->where('addon_details.addon_type_name', 'K')
            ->orderBy('addon_details.id', 'asc')
            ->get();
        $accessories = AddonDetails::select('addon_details.id','addon_details.addon_code',DB::raw("CONCAT(addons.name, 
                IF(addon_descriptions.description IS NOT NULL AND addon_descriptions.description != '', CONCAT(' - ', addon_descriptions.description), '')) as addon_name
                "),DB::raw("'App\\Models\\AddonDetails' as reference_type"))
            ->join('addon_descriptions', 'addon_details.description', '=', 'addon_descriptions.id')
            ->join('addons', 'addon_descriptions.addon_id', '=', 'addons.id')
            ->where('addon_details.addon_type_name', 'P')
            ->orderBy('addon_details.id', 'asc')
            ->get();
        $spareParts = AddonDetails::select('addon_details.id','addon_details.addon_code',DB::raw("CONCAT(addons.name, 
                IF(addon_descriptions.description IS NOT NULL AND addon_descriptions.description != '', CONCAT(' - ', addon_descriptions.description), '')) as addon_name
                "),DB::raw("'App\\Models\\AddonDetails' as reference_type"))
            ->join('addon_descriptions', 'addon_details.description', '=', 'addon_descriptions.id')
            ->join('addons', 'addon_descriptions.addon_id', '=', 'addons.id')
            ->where('addon_details.addon_type_name', 'SP')
            ->orderBy('addon_details.id', 'asc')
            ->get();
        $data['charges'] = MasterCharges::select('master_charges.id','master_charges.addon_code',DB::raw("CONCAT(
                IF(master_charges.name IS NOT NULL, master_charges.name, ''), 
                IF(master_charges.name IS NOT NULL AND master_charges.description IS NOT NULL, ' - ', ''), 
                IF(master_charges.description IS NOT NULL, master_charges.description, '')) as addon_name"),
                DB::raw("'App\\Models\\Masters\\MasterCharges' as reference_type"))
            ->orderBy('master_charges.id', 'asc')
            ->get();
        // Merge collections
        $data['addons'] = $accessories->merge($spareParts)->merge($kit);
                    return response()->json($data);
        // }

        // return response()->json([]);
    }
}
