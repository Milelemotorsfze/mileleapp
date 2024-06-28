<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WOVehicles;
use App\Models\WOVehicleRecordHistory;
use App\Models\WOVehicleAddons;
use App\Models\WOVehicleAddonRecordHistory;
use App\Models\WOComments;
use App\Models\WORecordHistory;
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
use Carbon\Carbon;
use Validator;
use Exception;
class WorkOrderController extends Controller
{
    public function workOrderCreate($type) {
        (new UserActivityController)->createActivity('Open '.$type.' work order create page');

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
        $charges = MasterCharges::select('master_charges.id','master_charges.addon_code',DB::raw("CONCAT(
                IF(master_charges.name IS NOT NULL, master_charges.name, ''), 
                IF(master_charges.name IS NOT NULL AND master_charges.description IS NOT NULL, ' - ', ''), 
                IF(master_charges.description IS NOT NULL, master_charges.description, '')) as addon_name"),
                DB::raw("'App\\Models\\Masters\\MasterCharges' as reference_type"))
            ->orderBy('master_charges.id', 'asc')
            ->get();
        // Merge collections
        $addons = $accessories->merge($spareParts)->merge($kit);

        // Select data from the WorkOrder table
        $workOrders = WorkOrder::select(
            'customer_name', 
            'customer_email', 
            'customer_company_number', 
            'customer_address',
            DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\WorkOrder' as reference_type"),
        );

        // Select and transform data from the Clients table
        $clients = Clients::select(
            DB::raw('name as customer_name'), 
            DB::raw('email as customer_email'), 
            DB::raw('phone as customer_company_number'), 
            DB::raw('NULL as customer_address'),
            DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\Clients' as reference_type"),
        );

        // Select and transform data from the Customer table
        $dpCustomers = Customer::select(
            DB::raw('name as customer_name'), 
            DB::raw('NULL as customer_email'), 
            DB::raw('NULL as customer_company_number'), 
            DB::raw('address as customer_address'),
            DB::raw('(IF(address IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\Customer' as reference_type"),
        )->distinct();

        // Combine the results
        $combinedResults = $workOrders->union($clients)->union($dpCustomers)->get();

        // Process combined results to remove duplicates based on customer_name
        $customers = $combinedResults->groupBy('customer_name')->map(function($items) {
            // Sort items by score in descending order and then take the first item
            return $items->sortByDesc('score')->first();
        })->values()->sortBy('customer_name');
        // Get the count of customers
        $customerCount = $customers->count();
        $users = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->get();
        $airlines = MasterAirlines::orderBy('name','ASC')->get();
        $vins = Vehicles::orderBy('vin','ASC')->whereNotNull('vin')->with('variant.master_model_lines.brand','interior','exterior','warehouseLocation','document')->get()->unique('vin');
        return view('work_order.export_exw.create',compact('type','customers','customerCount','airlines','vins','users','addons','charges'));
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
    public function store(StoreWorkOrderRequest $request)
    { 
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
            $input['created_by'] = $authId;
            $input['so_total_amount'] = $request->so_total_amount ?? 0.00;
            $input['amount_received'] = $request->amount_received ?? 0.00;
            $input['balance_amount'] = $request->balance_amount ?? 0.00;

            if ($request->customer_type == 'new') {
                $input['customer_name'] = $request->new_customer_name;
            } else if ($request->customer_type == 'existing') {
                $input['customer_name'] = $request->existing_customer_name;
            }

            $fields = [
                'air' => [
                    'brn', 'container_number', 'shipping_line', 'forward_import_code',
                    'trailer_number_plate', 'transportation_company',
                    'transporting_driver_contact_number', 'transportation_company_details'
                ],
                'sea' => [
                    'airline_reference_id', 'airline', 'airway_bill', 'trailer_number_plate',
                    'transportation_company', 'transporting_driver_contact_number',
                    'airway_details', 'transportation_company_details'
                ],
                'road' => [
                    'brn_file', 'brn', 'container_number', 'airline_reference_id', 'airline',
                    'airway_bill', 'shipping_line', 'airway_details', 'forward_import_code'
                ]
            ];
            
            $transportType = $request->transport_type;
            
            if (isset($fields[$transportType])) {
                foreach ($fields[$transportType] as $field) {
                    $input[$field] = NULL;
                }
            }

            $fileFields = [
                'brn_file' => 'wo/brn_file',
                'signed_pfi' => 'wo/signed_pfi',
                'signed_contract' => 'wo/signed_contract',
                'payment_receipts' => 'wo/payment_receipts',
                'noc' => 'wo/noc',
                'enduser_trade_license' => 'wo/enduser_trade_license',
                'enduser_passport' => 'wo/enduser_passport',
                'enduser_contract' => 'wo/enduser_contract',
                'vehicle_handover_person_id' => 'wo/vehicle_handover_person_id'
            ]; 
            // Loop through each file field
            foreach ($fileFields as $fileField => $path) {
                if ($request->hasFile($fileField)) {
                    $file = $request->file($fileField);
                    if ($file->isValid() && $file->getError() == UPLOAD_ERR_OK) {
                        $fileName = auth()->id() . '_' . time() . '.' . $file->extension();
                        $file->move(public_path($path), $fileName);

                        // Add the file name to the input array
                        $input[$fileField] = $fileName;

                        // Collect file metadata for data history
                        $fileData[] = [
                            'file_name' => $fileField,
                            'file_type' => $file->getClientMimeType(),
                            'file_path' => $path . '/' . $fileName
                        ];
                    }
                }
            }
            $workOrder = WorkOrder::create($input);
        
            // Handle customer name changes based on customer type
            if ($request->customer_type == 'new' && !is_null($request->new_customer_name)) {
                WORecordHistory::create([
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => NULL,
                    'new_value' => $request->new_customer_name,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ]);
            } elseif ($request->customer_type == 'existing' && !is_null($request->existing_customer_name)) {
                WORecordHistory::create([
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => NULL,
                    'new_value' => $request->existing_customer_name,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ]);
            }
           // Define the fields to exclude
            $excludeFields = [
                '_token', 'customerCount', 'type', 'customer_type', 'comments', 'currency', 
                'wo_id', 'new_customer_name', 'brn_file', 'signed_pfi', 'signed_contract', 
                'payment_receipts', 'noc', 'enduser_trade_license', 'enduser_passport', 
                'enduser_contract', 'vehicle_handover_person_id'
            ];

            // Filter out non-null, non-array values, and exclude specified fields
            $nonNullData = array_filter($request->all(), function ($value, $key) use ($excludeFields) {
                return !is_null($value) && !is_array($value) && !in_array($key, $excludeFields);
            }, ARRAY_FILTER_USE_BOTH);

            // Define specific nested fields to store if not null
            $nestedFields = [
                'customer_company_number' => 'full',
                'customer_representative_contact' => 'full',
                'freight_agent_contact_number' => 'full',
                'transporting_driver_contact_number' => 'full'
            ];

            // Store each non-null, non-array field in the data history
            foreach ($nonNullData as $field => $value) { 
                WORecordHistory::create([
                    'work_order_id' => $workOrder->id,
                    'field_name' => $field,
                    'old_value' => NULL,
                    'new_value' => $value,
                    'type' => 'Set',
                    'user_id' => Auth::id(),
                    'changed_at' => Carbon::now(),
                ]);

                // Store currency value conditionally based on the 'so_total_amount' field
                if($field == 'so_total_amount') {
                    WORecordHistory::create([
                        'work_order_id' => $workOrder->id,
                        'field_name' => 'currency',
                        'old_value' => NULL,
                        'new_value' => $request->currency,
                        'type' => 'Set',
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ]);
                }
            }

            // Store specific nested fields if not null
            foreach ($nestedFields as $field => $subField) {
                if (isset($request->$field[$subField]) && !is_null($request->$field[$subField])) {
                    WORecordHistory::create([
                        'work_order_id' => $workOrder->id,
                        'field_name' => $field . '.' . $subField,
                        'old_value' => NULL,
                        'new_value' => $request->$field[$subField],
                        'type' => 'Set',
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ]);
                }
            }

            // Store file information in the data history table
            if (!empty($fileData)) {
                foreach ($fileData as $data) {
                    WORecordHistory::create([
                        'work_order_id' => $workOrder->id,
                        'field_name' => $data['file_name'],
                        'old_value' => NULL,
                        'new_value' => $data['file_path'],
                        'type' => 'SET',
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ]);
                }
            }

            if (isset($request->vehicle)) {
                if (count($request->vehicle) > 0) {
                    foreach ($request->vehicle as $key => $vehicleData) {
                        $createWOVehicles = [];
                        $createWOVehicles['work_order_id'] = $workOrder->id;
                        $createWOVehicles['vehicle_id'] = $vehicleData['vehicle_id'] ?? null;
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

                        // Define the fields to exclude
                        $excludeVehicleFields = [
                            'vehicle_id'
                        ];

                        // Filter out non-null, non-array values, and exclude specified fields
                        $nonNullVehicleData = array_filter($vehicleData, function ($value, $key) use ($excludeVehicleFields) {
                            return !is_null($value) && !is_array($value) && !in_array($key, $excludeVehicleFields);
                        }, ARRAY_FILTER_USE_BOTH);

                        // Store each non-null, non-array field in the data history
                        foreach ($nonNullVehicleData as $field => $value) { 
                            WOVehicleRecordHistory::create([
                                'w_o_vehicle_id' => $woVehicles->id,
                                'field_name' => $field,
                                'old_value' => NULL,
                                'new_value' => $value,
                                'type' => 'Set',
                                'user_id' => Auth::id(),
                                'changed_at' => Carbon::now(),
                            ]);
                        }
                        if (isset($vehicleData['addons'])) {
                            if (count($vehicleData['addons']) > 0) {
                                foreach ($vehicleData['addons'] as $key => $addonData) {
                                    if (isset($addonData['addon_code']) && $addonData['addon_code'] != null) {
                                        $this->processNewAddons($woVehicles,$addonData,$authId);
                                    }
                                }
                            }
                        }       
                    }
                }
            }  
            // BOE
            if (isset($request->boe) && count($request->boe) > 0) {
                foreach ($request->boe as $boeNumber => $boe) {
                    if (isset($boe['vin']) && count($boe['vin']) > 0) {
                        foreach ($boe['vin'] as $vin) {
                            $vinUpdate = WOVehicles::where('vin', $vin)->where('work_order_id',$workOrder->id)->first();
                            if ($vinUpdate) {
                                $vinUpdate->boe_number = $boeNumber;
                                $vinUpdate->save();
                                WOVehicleRecordHistory::create([
                                    'w_o_vehicle_id' => $vinUpdate->id,
                                    'field_name' => 'boe_number',
                                    'old_value' => NULL,
                                    'new_value' => $boeNumber,
                                    'type' => 'Set',
                                    'user_id' => Auth::id(),
                                    'changed_at' => Carbon::now(),
                                ]);
                            }
                        }
                    }
                }
            }

            // Deposit against vehicles
            if (isset($request->deposit_received_as) && $request->deposit_received_as === 'custom_deposit') {
                if (isset($request->deposit_aganist_vehicle) && is_array($request->deposit_aganist_vehicle) && count($request->deposit_aganist_vehicle) > 0) {
                    foreach ($request->deposit_aganist_vehicle as $vin) {
                        $vinUpdate = WOVehicles::where('vin', $vin)->where('work_order_id',$workOrder->id)->first();
                        if ($vinUpdate) {
                            $vinUpdate->deposit_received = 'yes';
                            $vinUpdate->save();
                            WOVehicleRecordHistory::create([
                                'w_o_vehicle_id' => $vinUpdate->id,
                                'field_name' => 'boe_number',
                                'old_value' => NULL,
                                'new_value' => 'yes',
                                'type' => 'Set',
                                'user_id' => Auth::id(),
                                'changed_at' => Carbon::now(),
                            ]);
                        }
                    }
                }
            }
            // Initialize an array to keep track of old to new comment IDs
            $commentIdMap = [];

            // Handle comments
            $comments = json_decode($request->input('comments'), true);
            foreach ($comments as $comment) {
                $newComment = WOComments::create([
                    'work_order_id' => $workOrder->id,
                    'text' => $comment['text'],
                    'parent_id' => null, // Temporary null, will update later
                    'user_id' => auth()->id(),
                ]);
                // Map the old comment ID to the new comment ID
                $commentIdMap[$comment['commentId']] = $newComment->id;
            }

            // Update parent IDs
            foreach ($comments as $comment) {
                if (!empty($comment['parentId'])) {
                    $newCommentId = $commentIdMap[$comment['commentId']];
                    $newParentId = $commentIdMap[$comment['parentId']];
                    WOComments::where('id', $newCommentId)->update(['parent_id' => $newParentId]);
                }
            }    
            (new UserActivityController)->createActivity('Create '.$request->type.' work order');
            // Commit the transaction
            DB::commit(); 
            return response()->json(['success' => true, 'message' => 'Work order created successfully.']);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error creating Work Order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function processNewAddons($woVehicles,$addonData,$authId) { 
        $createWOVehiclesAddons = [];
        $createWOVehiclesAddons['w_o_vehicle_id'] = $woVehicles->id;                          
        // $createWOVehiclesAddons['addon_reference_id'] = $addonData['vin'] ?? null;
        // $createWOVehiclesAddons['addon_reference_type'] = $addonData['brand'] ?? null;
        $createWOVehiclesAddons['addon_code'] = $addonData['addon_code'] ?? null;
        // $createWOVehiclesAddons['addon_name'] = $addonData['addon_name'] ?? null;
        // $createWOVehiclesAddons['addon_name_description'] = $addonData['addon_name_description'] ?? null;
        $createWOVehiclesAddons['addon_quantity'] = $addonData['addon_quantity'] ?? null;
        $createWOVehiclesAddons['addon_description'] = $addonData['addon_description'] ?? null;                                  
        $createWOVehiclesAddons['created_by'] = $authId;
    
        $WOVehicleAddons = WOVehicleAddons::create($createWOVehiclesAddons); 
        // Filter out non-null, non-array values, and exclude specified fields
        $excludeVehicleAddonFields = [
            'id','w_o_vehicle_id',
        ];
        $nonNullVehicleAddonData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
            return !is_null($value) && !in_array($key, $excludeVehicleAddonFields);
        }, ARRAY_FILTER_USE_BOTH);
       
    
        // Store each non-null, non-array field in the data history
        foreach ($nonNullVehicleAddonData as $field => $value) {  
            WOVehicleAddonRecordHistory::create([
                'w_o_vehicle_addon_id' => $WOVehicleAddons->id,
                'field_name' => $field,
                'old_value' => NULL,
                'new_value' => $value,
                'type' => 'Set',
                'user_id' => Auth::id(),
                'changed_at' => Carbon::now(),
            ]);
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
        // $errorMsg ="This page will coming very soon !";
        // return view('hrm.notaccess',compact('errorMsg'));
        $type = $workOrder->type;
        $workOrder = WorkOrder::where('id',$workOrder->id)->with('comments')->first();
        $previous = WorkOrder::where('type',$type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type',$type)->where('id', '>', $workOrder->id)->min('id');
        $users = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->get();
        return view('work_order.export_exw.show',compact('type','users','workOrder','previous','next'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        $type = $workOrder->type;
        $workOrder = WorkOrder::where('id',$workOrder->id)->with('vehicles.addons')->first();
        // $dpCustomers = Customer::select(DB::raw('name as customer_name'), DB::raw('NULL as customer_email'), DB::raw('NULL as customer_company_number'), DB::raw('address as customer_address'))->distinct();
        // $clients = Clients::select(DB::raw('name as customer_name'), DB::raw('email as customer_email'),DB::raw('phone as customer_company_number'), DB::raw('NULL as customer_address'))->distinct();
        // // $workOrders = WorkOrder::select('customer_name', 'customer_email', 'customer_company_number', 'customer_address')->distinct();
        
        // $customers = $dpCustomers->union($clients)->get();
        // // ->union($workOrders)
        // $customers = $customers->unique('customer_name');
        // // $errorMsg ="This page will coming very soon !";
        // // return view('hrm.notaccess',compact('errorMsg'));
         // Select data from the WorkOrder table
         $workOrders = WorkOrder::select(
            'customer_name', 
            'customer_email', 
            'customer_company_number', 
            'customer_address',
            DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\WorkOrder' as reference_type"),
        );

        // Select and transform data from the Clients table
        $clients = Clients::select(
            DB::raw('name as customer_name'), 
            DB::raw('email as customer_email'), 
            DB::raw('phone as customer_company_number'), 
            DB::raw('NULL as customer_address'),
            DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\Clients' as reference_type"),
        );

        // Select and transform data from the Customer table
        $dpCustomers = Customer::select(
            DB::raw('name as customer_name'), 
            DB::raw('NULL as customer_email'), 
            DB::raw('NULL as customer_company_number'), 
            DB::raw('address as customer_address'),
            DB::raw('(IF(address IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\Customer' as reference_type"),
        )->distinct();

        // Combine the results
        $combinedResults = $workOrders->union($clients)->union($dpCustomers)->get();

        // Process combined results to remove duplicates based on customer_name
        $customers = $combinedResults->groupBy('customer_name')->map(function($items) {
            // Sort items by score in descending order and then take the first item
            return $items->sortByDesc('score')->first();
        })->values()->sortBy('customer_name');
        // Get the count of customers
        $customerCount = $customers->count();
        $users = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->get();
        // $accSpaKits = AddonDetails::select('addon_code')->distinct();
        
        $airlines = MasterAirlines::orderBy('name','ASC')->get();
        $vins = Vehicles::orderBy('vin','ASC')->whereNotNull('vin')->with('variant.master_model_lines.brand','interior','exterior','warehouseLocation','document')->get()->unique('vin');
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
        $charges = MasterCharges::select('master_charges.id','master_charges.addon_code',DB::raw("CONCAT(
                IF(master_charges.name IS NOT NULL, master_charges.name, ''), 
                IF(master_charges.name IS NOT NULL AND master_charges.description IS NOT NULL, ' - ', ''), 
                IF(master_charges.description IS NOT NULL, master_charges.description, '')) as addon_name"),
                DB::raw("'App\\Models\\Masters\\MasterCharges' as reference_type"))
            ->orderBy('master_charges.id', 'asc')
            ->get();
        // Merge collections
        $addons = $accessories->merge($spareParts)->merge($kit);
        return view('work_order.export_exw.create',compact('workOrder','customerCount','type','customers','airlines','vins','users','addons','charges'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    { 
        DB::beginTransaction();
        try { 
            $authId = Auth::id();
            $newData = $request->all();
    
            // Extract full values for specific nested fields
            $nestedFields = [
                'customer_company_number',
                'customer_representative_contact',
                'freight_agent_contact_number',
                'transporting_driver_contact_number'
            ];
            
            foreach ($nestedFields as $field) {
                $newData[$field] = $request->$field['full'] ?? null;
            }
    
            // Additional data processing
            $newData['updated_by'] = $authId;
            $newData['so_total_amount'] = $request->so_total_amount ?? 0.00;
            $newData['amount_received'] = $request->amount_received ?? 0.00;
            $newData['balance_amount'] = $request->balance_amount ?? 0.00;
    
            if ($request->customer_type == 'new') {
                $newData['customer_name'] = $request->new_customer_name;
            } else if ($request->customer_type == 'existing') {
                $newData['customer_name'] = $request->existing_customer_name;
            }
    
            // Reset fields based on transport type
            $fields = [
                'air' => ['brn', 'container_number', 'shipping_line', 'forward_import_code', 'trailer_number_plate', 'transportation_company', 'transporting_driver_contact_number', 'transportation_company_details'],
                'sea' => ['airline_reference_id', 'airline', 'airway_bill', 'trailer_number_plate', 'transportation_company', 'transporting_driver_contact_number', 'airway_details', 'transportation_company_details'],
                'road' => ['brn_file', 'brn', 'container_number', 'airline_reference_id', 'airline', 'airway_bill', 'shipping_line', 'airway_details', 'forward_import_code']
            ];
            
            $transportType = $request->transport_type;
            if (isset($fields[$transportType])) {
                foreach ($fields[$transportType] as $field) {
                    $newData[$field] = null;
                }
            }

            // Helper function to handle file upload and history recording
            function handleFileUpload($request, $fileKey, $path, &$newData, $workOrder, $oldData, $deleteFlag = null) {
                $fileName = null;
                if ($request->hasFile($fileKey)) {
                    $fileName = auth()->id() . '_' . time() . '.' . $request->file($fileKey)->extension();
                    $type = $request->file($fileKey)->getClientMimeType();
                    $size = $request->file($fileKey)->getSize();
                    $request->file($fileKey)->move(public_path($path), $fileName);
                }
                if (isset($fileName)) {
                    $newData[$fileKey] = $fileName;
                } elseif ($deleteFlag && $request->input($deleteFlag) == 1) {
                    $newData[$fileKey] = NULL;
                }

                $oldValue = $oldData[$fileKey] ?? NULL;
                $newValue = $newData[$fileKey] ?? NULL;

                if ($oldValue != $newValue) {
                    if ($oldValue != NULL && $newValue != NULL) {
                        $type = 'Change';
                        $newFilePath = 'wo/' . $fileKey . '/' . $newValue;
                        $oldFilePath = 'wo/' . $fileKey . '/' . $oldValue;
                    } elseif ($oldValue == NULL && $newValue != NULL) {
                        $type = 'Set';
                        $newFilePath = 'wo/' . $fileKey . '/' . $newValue;
                        $oldFilePath = NULL;
                    } elseif ($oldValue != NULL && $newValue == NULL) {
                        $type = 'Unset';
                        $newFilePath = NULL;
                        $oldFilePath = 'wo/' . $fileKey . '/' . $oldValue;
                    }
                    WORecordHistory::create([
                        'work_order_id' => $workOrder->id,
                        'field_name' => $fileKey,
                        'old_value' => $oldFilePath ?? NULL,
                        'new_value' => $newFilePath,
                        'type' => $type,
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ]);
                }
            }

            // Prepare old data for comparison
            $oldData = $workOrder->getOriginal();

            // Initialize newData array
            $newData = [];

            // Handle file uploads and history for various files
            $filesToHandle = [
                'brn_file' => 'wo/brn_file',
                'signed_pfi' => 'wo/signed_pfi',
                'signed_contract' => 'wo/signed_contract',
                'payment_receipts' => 'wo/payment_receipts',
                'noc' => 'wo/noc',
                'enduser_trade_license' => 'wo/enduser_trade_license',
                'enduser_passport' => 'wo/enduser_passport',
                'enduser_contract' => 'wo/enduser_contract',
                'vehicle_handover_person_id' => 'wo/vehicle_handover_person_id'
            ];

            foreach ($filesToHandle as $fileKey => $path) {
                handleFileUpload($request, $fileKey, $path, $newData, $workOrder, $oldData, 'is_' . $fileKey . '_delete');
            }
    
            // List of fields to exclude
            $excludeFields = [
                '_method', '_token', 'customerCount', 'type', 'customer_type', 'comments', 'currency', 'wo_id', 'updated_by',
                'brn_file', 'signed_pfi', 'signed_contract', 'payment_receipts', 'noc', 'enduser_trade_license',
                'enduser_passport', 'enduser_contract', 'vehicle_handover_person_id', 'new_customer_name', 'existing_customer_name',
                'is_brn_file_delete','is_signed_pfi_delete','is_signed_contract_delete','is_payment_receipts_delete','is_noc_delete',
                'is_enduser_trade_license_delete','is_enduser_passport_delete','is_enduser_contract_delete','is_vehicle_handover_person_id_delete',
            ];

            // Filter $newData to exclude array values and fields in the exclude list
            $filteredNewData = array_filter($newData, function ($value, $key) use ($excludeFields) {
                return !is_array($value) && !in_array($key, $excludeFields);
            }, ARRAY_FILTER_USE_BOTH);

            // Initialize an array to hold the changes
            $changes = [];

            // Iterate through filtered $newData
            foreach ($filteredNewData as $field => $newValue) {
                // Get the old value if it exists
                $oldValue = $oldData[$field] ?? null;

                // Check if the old value is different from the new value
                if ($oldValue != $newValue) {
                    // Determine the type of change
                    $changeType = 'Change';
                    if (in_array($field, ['so_total_amount', 'amount_received', 'balance_amount'])) {
                        if ($oldValue == 0.00 && ($newValue != 0.00 || !is_null($newValue))) {
                            $changeType = 'Set';
                            $oldValue = NULL;
                        } elseif ($oldValue != 0.00 && ($newValue == 0.00 || is_null($newValue))) {
                            $changeType = 'Unset';
                            $newValue = NULL;
                        }
                    } else {
                        if (is_null($oldValue) && !is_null($newValue)) {
                            $changeType = 'Set';
                        } elseif (!is_null($oldValue) && is_null($newValue)) {
                            $changeType = 'Unset';
                        }
                    }

                    // Add the change to the changes array
                    $changes[] = [
                        'work_order_id' => $workOrder->id,
                        'user_id' => $authId,
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'type' => $changeType,
                        'changed_at' => Carbon::now()
                    ];
                }
            }

            // Handle customer name changes based on customer type
            if ($request->customer_type == 'new' && $oldData['customer_name'] != $request->new_customer_name) {
                $changeType = 'Change';
                if (is_null($oldData['customer_name']) && !is_null($request->new_customer_name)) {
                    $changeType = 'Set';
                } elseif (!is_null($oldData['customer_name']) && is_null($request->new_customer_name)) {
                    $changeType = 'Unset';
                }
                $changes[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => $oldData['customer_name'],
                    'new_value' => $request->new_customer_name,
                    'type' => $changeType,
                    'changed_at' => Carbon::now()
                ];
            } elseif ($request->customer_type == 'existing' && $oldData['customer_name'] != $request->existing_customer_name) {
                $changeType = 'Change';
                if (is_null($oldData['customer_name']) && !is_null($request->existing_customer_name)) {
                    $changeType = 'Set';
                } elseif (!is_null($oldData['customer_name']) && is_null($request->existing_customer_name)) {
                    $changeType = 'Unset';
                }
                $changes[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => $oldData['customer_name'],
                    'new_value' => $request->existing_customer_name,
                    'type' => $changeType,
                    'changed_at' => Carbon::now()
                ];
            }
            // Handle currency changes based on SO Amount, Amount Received and Balance Amount
            if($oldData['currency'] != $request->currency) {
                $changeType = 'Change';
                if(is_null($oldData['currency']) && !is_null($request->currency)) {
                    $changeType = 'Set';
                }
                else if(!is_null($oldData['currency']) && is_null($request->currency)) {
                    $changeType = 'Unset';
                }
                $changes[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'currency',
                    'old_value' => $oldData['currency'],
                    'new_value' => $request->currency,
                    'type' => $changeType,
                    'changed_at' => Carbon::now()
                ];
            }
            // If there are changes, insert them into the WORecordHistory
            if (!empty($changes)) { 
                WORecordHistory::insert($changes);
            }
            // Update the WorkOrder
            $workOrder->update($newData);

            // VEHICLES START.....................................

            // Assuming $request->vehicles is an array of vehicles with unique VINs
            $vehiclesData = $request->vehicle ?? [];
            // Ensure $vehiclesData is an array
            if (!is_array($vehiclesData) || empty($vehiclesData)) {
                // Handle the case where $vehiclesData is not an array or is empty
                $vehiclesData = [];
            }

            // Extract the id of vehicles from the incoming request data
            $incomingIds = array_column($vehiclesData, 'id');

            // Get the existing vehicles from the database
            $existingVehicles = WOVehicles::whereIn('id', $incomingIds)->get()->keyBy('id');

            // Track vehicles that were processed
            $processedIds = [];

            foreach ($vehiclesData as $vehicleData) {
                $id = $vehicleData['id'];
                // Define the fields to exclude
                $excludeVehicleFields = [
                    'id','work_order_id','vehicle_id','updated_by','created_by',
                ];
                // Update if exists, otherwise create
                if (isset($existingVehicles[$id])) {
                    
                    // Mark this VIN as processed
                    $processedIds[] = $id; 
                    $vehicle = $existingVehicles[$id];
                    $vehicleData['updated_by'] = Auth::id();
                    // Filter out non-null, non-array values, and exclude specified fields
                    $filterredVehicleData = array_filter($vehicleData, function ($value, $key) use ($excludeVehicleFields) {
                        return !is_array($value) && !in_array($key, $excludeVehicleFields);
                    }, ARRAY_FILTER_USE_BOTH);
                    // Check and store only changed fields
                    foreach ($filterredVehicleData as $field => $newValue) {
                        $oldValue = $vehicle->$field;
                        if ($oldValue !== $newValue) {
                            $changeType = 'Change';
                            if (is_null($oldValue) && !is_null($newValue)) {
                                $changeType = 'Set';
                            } elseif (!is_null($oldValue) && is_null($newValue)) {
                                $changeType = 'Unset';
                            }
                            // Change the vehicle data
                            $vehicle->$field = $newValue;

                            // Store the change in history
                            WOVehicleRecordHistory::create([
                                'w_o_vehicle_id' => $vehicle->id,
                                'field_name' => $field,
                                'old_value' => $oldValue,
                                'new_value' => $newValue,
                                'type' => $changeType,
                                'user_id' => Auth::id(),
                                'changed_at' => Carbon::now(),
                            ]);
                        }
                    }
                    
                    // Save the vehicle with updated data
                    $vehicle->save();
                 // ADDON START....................

                // Assuming $vehicleData['addons'] is an array of vehicle addons with unique id
                $vehicleAddonsData = $vehicleData['addons'] ?? [];
                // Ensure $vehicleAddonsData is an array
                if (!is_array($vehicleAddonsData) || empty($vehicleAddonsData)) {
                    // Handle the case where $vehicleAddonsData is not an array or is empty
                    $vehicleAddonsData = [];
                }
                // Extract the ID of addons from the incoming request data
                $incomingAddonIds = array_column($vehicleAddonsData, 'id');
                // Get the existing addons from the database
                $existingAddons = WOVehicleAddons::whereIn('id', $incomingAddonIds)->get()->keyBy('id');

                // Track addons that were processed
                $processedAddonIds = [];
                foreach ($vehicleAddonsData as $addonData) {
                    $addonId = $addonData['id'] ?? null;
                    // Define the fields to exclude
                    $excludeVehicleAddonFields = [
                        'id','work_order_id','w_o_vehicle_id', 'w_o_vehicle_addon_id','vehicle_id','updated_by','created_by',
                    ];
                    // Update if exists, otherwise create
                    if (isset($existingAddons[$addonId])) { 
                        $processedAddonIds[] = $addonId; // Append ID to array
                        $addon = $existingAddons[$addonId];
                        $addonData['updated_by'] = Auth::id();
                        // Filter out non-null, non-array values, and exclude specified fields
                        $filterredVehicleAddonData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
                            return !is_array($value) && !in_array($key, $excludeVehicleAddonFields);
                        }, ARRAY_FILTER_USE_BOTH);
                        // Check and store only changed fields
                        foreach ($filterredVehicleAddonData as $field => $newValue) {
                            $oldValue = $addon->$field;
                            
                            // Trim string values
                            if (is_string($oldValue)) {
                                $oldValue = trim($oldValue);
                            }
                            if (is_string($newValue)) {
                                $newValue = trim($newValue);
                            }
                            
                            // Convert numeric strings to numbers
                            if (is_numeric($oldValue) && is_numeric($newValue)) {
                                $oldValue = (float)$oldValue;
                                $newValue = (float)$newValue;
                            }
                        
                            if ($oldValue !== $newValue) {
                                
                                $changeType = 'Change';
                                if (is_null($oldValue) && !is_null($newValue)) {
                                    $changeType = 'Set';
                                } elseif (!is_null($oldValue) && is_null($newValue)) {
                                    $changeType = 'Unset';
                                }
                        
                                // Change the vehicle data
                                $addon->$field = $newValue;
                        
                                // Store the change in history
                                WOVehicleAddonRecordHistory::create([
                                    'w_o_vehicle_addon_id' => $addon->id,
                                    'field_name' => $field,
                                    'old_value' => $oldValue,
                                    'new_value' => $newValue,
                                    'type' => $changeType,
                                    'user_id' => Auth::id(),
                                    'changed_at' => Carbon::now(),
                                ]);
                            }
                        }
                        
                        // Save the vehicle with updated data
                        $addon->save();
                    } else {
                        $addonData['w_o_vehicle_id'] = $vehicle->id;
                        $addonData['created_by'] = Auth::id();
                        $woVehicleAddon = WOVehicleAddons::create($addonData);
                        $processedAddonIds[] = $woVehicleAddon->id; // Append ID to array
                        // Filter out non-null, non-array values, and exclude specified fields
                        $nonNullVehicleData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
                            return !is_null($value) && !is_array($value) && !in_array($key, $excludeVehicleAddonFields);
                        }, ARRAY_FILTER_USE_BOTH);

                        // Store each non-null, non-array field in the data history
                        foreach ($nonNullVehicleData as $field => $value) { 
                            WOVehicleAddonRecordHistory::create([
                                'w_o_vehicle_addon_id' => $woVehicleAddon->id,
                                'field_name' => $field,
                                'old_value' => NULL,
                                'new_value' => $value,
                                'type' => 'Set',
                                'user_id' => Auth::id(),
                                'changed_at' => Carbon::now(),
                            ]);
                        }
                    }
                }

                // Ensure $processedIds only contains valid IDs
                $processedAddonIds = array_filter($processedAddonIds, function($id) {
                    return !is_null($id);
                });
                // Retrieve addons that were not in the incoming request and update deleted_by field
                $addonsToDelete = WOVehicleAddons::whereNotIn('id', $processedAddonIds)->where('w_o_vehicle_id', $vehicle->id)->get();
                foreach ($addonsToDelete as $addon) {
                    $addon->deleted_by = Auth::id();
                    $addon->save();
                }

                // Now delete the addons
                WOVehicleAddons::whereNotIn('id', $processedAddonIds)->where('w_o_vehicle_id', $vehicle->id)->delete();

                // ADDON END..............................
                } else { 
                    $vehicleData['work_order_id'] = $workOrder->id;
                    $vehicleData['created_by'] = Auth::id();
                    $woVehicles = WOVehicles::create($vehicleData);
                    // Filter out non-null, non-array values, and exclude specified fields
                    $nonNullVehicleData = array_filter($vehicleData, function ($value, $key) use ($excludeVehicleFields) {
                        return !is_null($value) && !is_array($value) && !in_array($key, $excludeVehicleFields);
                    }, ARRAY_FILTER_USE_BOTH);

                    // Store each non-null, non-array field in the data history
                    foreach ($nonNullVehicleData as $field => $value) { 
                        WOVehicleRecordHistory::create([
                            'w_o_vehicle_id' => $woVehicles->id,
                            'field_name' => $field,
                            'old_value' => NULL,
                            'new_value' => $value,
                            'type' => 'Set',
                            'user_id' => Auth::id(),
                            'changed_at' => Carbon::now(),
                        ]);
                    }


                    // ADDON START.................
                    if (isset($vehicleData['addons'])) {
                        if (count($vehicleData['addons']) > 0) {
                            foreach ($vehicleData['addons'] as $key => $addonData) {
                                if (isset($addonData['addon_code']) && $addonData['addon_code'] != null) {
                                    // $this->processNewAddons($woVehicles,$addonData,$authId);
                                    $createWOVehiclesAddons = [];
                                    $createWOVehiclesAddons['w_o_vehicle_id'] = $woVehicles->id;                          
                                    // $createWOVehiclesAddons['addon_reference_id'] = $addonData['vin'] ?? null;
                                    // $createWOVehiclesAddons['addon_reference_type'] = $addonData['brand'] ?? null;
                                    $createWOVehiclesAddons['addon_code'] = $addonData['addon_code'] ?? null;
                                    // $createWOVehiclesAddons['addon_name'] = $addonData['addon_name'] ?? null;
                                    // $createWOVehiclesAddons['addon_name_description'] = $addonData['addon_name_description'] ?? null;
                                    $createWOVehiclesAddons['addon_quantity'] = $addonData['addon_quantity'] ?? null;
                                    $createWOVehiclesAddons['addon_description'] = $addonData['addon_description'] ?? null;                                  
                                    $createWOVehiclesAddons['created_by'] = $authId;
                                
                                    $WOVehicleAddons = WOVehicleAddons::create($createWOVehiclesAddons); 
                                    // Filter out non-null, non-array values, and exclude specified fields
                                    $excludeVehicleAddonFields = [
                                        'id','w_o_vehicle_id',
                                    ];
                                    $nonNullVehicleAddonData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
                                        return !is_null($value) && !in_array($key, $excludeVehicleAddonFields);
                                    }, ARRAY_FILTER_USE_BOTH);
                                   
                                
                                    // Store each non-null, non-array field in the data history
                                    foreach ($nonNullVehicleAddonData as $field => $value) {  
                                        WOVehicleAddonRecordHistory::create([
                                            'w_o_vehicle_addon_id' => $WOVehicleAddons->id,
                                            'field_name' => $field,
                                            'old_value' => NULL,
                                            'new_value' => $value,
                                            'type' => 'Set',
                                            'user_id' => Auth::id(),
                                            'changed_at' => Carbon::now(),
                                        ]);
                                    }
                                    // Mark this addon as processed
                                }
                            }
                        }
                    }  
                    // ADDON END ..................  
                    $processedIds[] = $woVehicles->id;
                }
            }
           // Ensure $processedIds only contains valid IDs
            $processedIds = array_filter($processedIds, function($id) {
                return !is_null($id);
            });

            $vehiclesToDelete = WOVehicles::whereNotIn('id', $processedIds)->where('work_order_id', $workOrder->id)->get();
            foreach ($vehiclesToDelete as $vehicle) {
                $vehicle->deleted_by = Auth::id();
                $vehicle->save();
            }

            // Now delete the vehicles
            WOVehicles::whereNotIn('id', $processedIds)->where('work_order_id',$workOrder->id)->delete();

            // VEHICLES END ........................................

            (new UserActivityController)->createActivity('Update ' . $request->type . ' work order');
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Work order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Work Order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
    public function storeComments(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:w_o_comments,id',
            'work_order_id' => 'required|integer|exists:work_orders,id'
        ]);

        $comment = WOComments::create([
            'work_order_id' => $request->input('work_order_id'),
            'text' => $request->input('text'),
            'parent_id' => $request->input('parent_id'),
            'user_id' => auth()->id(), // Assuming you're using Laravel's authentication
        ]);

        return response()->json($comment, 201);
    }
    // public function getComments($workOrderId)
    // {
    //     // Fetch comments for the specified work order
    //     $comments = WOComments::where('work_order_id', $workOrderId)->get();
    //     return response()->json(['comments' => $comments]);
    // }
    public function getComments($workOrderId)
    {
        $comments = WOComments::where('work_order_id', $workOrderId)->get();
        return response()->json(['comments' => $comments]);
    }
    public function uniqueSO(Request $request) { 
        $validator = Validator::make($request->all(), [
            'so_number' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            try {
                $wo = WorkOrder::where('so_number',$request->so_number);
                if($request->id != NULL || $request->id != '') { 
                    $wo = $wo->whereNot('id',$request->id);
                }
                $wo =$wo->get();
                if(count($wo) > 0) {
                    return false;
                }
                else {
                    return true;
                }
           } 
           catch (\Exception $e) {
               dd($e);
           }
        }
    }
    public function vehicleDataHistory($id) {
        $datas = WOVehicleRecordHistory::where('w_o_vehicle_id',$id)->get();
        return view('work_order.export_exw.show_vehicle_history',compact('datas'));
    }
    public function vehicleAddonDataHistory($id) {
        $datas = WOVehicleAddonRecordHistory::where('w_o_vehicle_addon_id',$id)->get();
        return view('work_order.export_exw.show_vehicle_addon_history',compact('datas'));
    }
    
}
