<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WOVehicles;
use App\Models\WOVehicleRecordHistory;
use App\Models\WOVehicleAddons;
use App\Models\WOVehicleAddonRecordHistory;
use App\Models\WOComments;
use App\Models\CommentVehicleMapping;
use App\Models\CommentVehicleAddonMapping;
use App\Models\CommentFile;
use App\Models\WORecordHistory;
use App\Models\WOApprovals;
use App\Models\WOApprovalDataHistory;
use App\Models\WOApprovalDepositAganistVehicle;
use App\Models\WOApprovalAddonDataHistory;
use App\Models\WOApprovalVehicleDataHistory;
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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
class WorkOrderController extends Controller
{
    public function workOrderCreate($type) {
        $authId = Auth::id();
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
        // Store permission checks
        $hasFullAccess = Auth::user()->hasPermissionForSelectedRole([
            'list-export-exw-wo', 'list-export-cnf-wo', 'list-export-local-sale-wo'
        ]);

        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'view-current-user-export-exw-wo-list', 'view-current-user-export-cnf-wo-list', 'view-current-user-local-sale-wo-list'
        ]);
        // Select data from the WorkOrder table
        $workOrders = WorkOrder::select(
            DB::raw('TRIM(customer_name) as customer_name'), 
            'customer_email', 
            'customer_company_number', 
            'customer_address',
            DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\WorkOrder' as reference_type"),
            DB::raw('NULL as country_id'), // Assuming WorkOrder does not have is_demand_planning_customer field
            DB::raw('NULL as is_demand_planning_customer'),
            DB::raw("CONCAT(TRIM(customer_name), '_', IFNULL(customer_email, ''), '_', IFNULL(customer_company_number, '')) as unique_id")
        );

        // Select and transform data from the Clients table
        $clients = Clients::select(
            DB::raw('TRIM(name) as customer_name'), 
            DB::raw('email as customer_email'), 
            DB::raw('phone as customer_company_number'), 
            DB::raw('NULL as customer_address'),
            DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\Clients' as reference_type"),
            'country_id',
            'is_demand_planning_customer',
            DB::raw("CONCAT(TRIM(name), '_', IFNULL(email, ''), '_', IFNULL(phone, ''), '_', IFNULL(country_id, '')) as unique_id")
        );

        // Apply the permission-based condition
        if ($hasLimitedAccess) {
            // Add the created_by condition for limited access
            $workOrders->where('created_by', $authId);
            $clients->where('created_by', $authId);
        }

        // Combine the results using union
        $combinedResults = $workOrders
            ->union($clients)
            ->get();

        // Clean up customer names in PHP
        $combinedResults = $combinedResults->map(function ($item) {
            // Replace multiple spaces with a single space
            $item->customer_name = preg_replace('/\s+/', ' ', trim($item->customer_name));
            return $item;
        });

        // Process combined results to remove duplicates based on unique_id
        $customers = $combinedResults->groupBy('unique_id')->map(function($items) {
            // Sort items by score in descending order and then take the first item
            return $items->sortByDesc('score')->first();
        })->values()->sortBy('customer_name');

        // Get the count of customers
        $customerCount = $customers->count();

        $users = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->get();
        $airlines = MasterAirlines::orderBy('name','ASC')->get();
        $vins = Vehicles::orderBy('vin','ASC')->whereNotNull('vin')->with('variant.master_model_lines.brand','interior','exterior','warehouseLocation','document')->get()->unique('vin')
            ->values(); // Reset the keys to ensure it's a proper array 
        return view('work_order.export_exw.create', compact('type', 'customers', 'customerCount', 'airlines', 'vins', 'users', 'addons', 'charges'))->with([
            'vinsJson' => $vins->toJson(), // Single encoding here
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index($type)
    {
        $authId = Auth::id();

        // Store permission checks
        $hasFullAccess = Auth::user()->hasPermissionForSelectedRole([
            'list-export-exw-wo', 'list-export-cnf-wo', 'list-export-local-sale-wo'
        ]);

        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'view-current-user-export-exw-wo-list', 'view-current-user-export-cnf-wo-list', 'view-current-user-local-sale-wo-list'
        ]);

        // Build the query with conditional adjustments
        $datas = WorkOrder::where('type', $type)
            ->when($hasLimitedAccess, function ($query) use ($authId) {
                return $query->where('created_by', $authId);
            })
            ->latest()
            ->get();

        return view('work_order.export_exw.index', compact('type', 'datas'));
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
            $input['delivery_contact_person_number'] = $request->delivery_contact_person_number['full'] ?? null;
            $input['freight_agent_contact_number'] = $request->freight_agent_contact_number['full'] ?? null;
            $input['transporting_driver_contact_number'] = $request->transporting_driver_contact_number['full'] ?? null;
            $input['created_by'] = $authId;
            $input['so_total_amount'] = $request->so_total_amount ?? 0.00;
            $input['amount_received'] = $request->amount_received ?? 0.00;
            $input['balance_amount'] = $request->balance_amount ?? 0.00;
            $input['date'] = Carbon::now()->format('Y-m-d');

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
            if(!isset($request->is_batch)) {
                $input['batch'] = NULL;
                $input['is_batch'] = 0;
            }
            else {
                $input['is_batch'] = 1;
            }
            $workOrder = WorkOrder::create($input);
        
            if(isset($request->is_batch)) {
                WORecordHistory::create([
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'batch',
                    'old_value' => NULL,
                    'new_value' => $request->batch,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ]);
                WORecordHistory::create([
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'is_batch',
                    'old_value' => NULL,
                    'new_value' => 1,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ]);               
            }
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
                'enduser_contract', 'vehicle_handover_person_id','batch','is_batch'
            ];

            // Filter out non-null, non-array values, and exclude specified fields
            $nonNullData = array_filter($request->all(), function ($value, $key) use ($excludeFields) {
                return !is_null($value) && !is_array($value) && !in_array($key, $excludeFields);
            }, ARRAY_FILTER_USE_BOTH);

            // Define specific nested fields to store if not null
            $nestedFields = [
                'customer_company_number' => 'full',
                'customer_representative_contact' => 'full',
                'delivery_contact_person_number' => 'full',
                'freight_agent_contact_number' => 'full',
                'transporting_driver_contact_number' => 'full'
            ];
            $canCreateFinanceApproval = false;
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
            
                // Check for specified fields and create an entry in the WOApprovals model
                if (in_array($field, ['so_total_amount', 'so_vehicle_quantity', 'amount_received', 'balance_amount'])) {
                    // , 'currency'
                    $canCreateFinanceApproval = true;
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
                        'type' => 'Set',
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ]);
                }
            }
            $canCreateCOOApproval = false;
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
                        $canCreateCOOApproval = true;
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
                                        $canCreateFinanceApproval = $this->processNewAddons($woVehicles, $addonData, $authId);
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
                                'field_name' => 'deposit_received',
                                'old_value' => NULL,
                                'new_value' => 'yes',
                                'type' => 'Set',
                                'user_id' => Auth::id(),
                                'changed_at' => Carbon::now(),
                            ]);
                            $canCreateFinanceApproval = true;
                        }
                    }
                }
            }
            // Initialize an array to keep track of old to new comment IDs
            // Handle comments
            $comments = json_decode($request->input('comments'), true);
            $commentIdMap = [];

            if (isset($comments) && $comments != null) { 
                // First pass: Create all comments and map their IDs
                foreach ($comments as $comment) {
                    $newComment = WOComments::create([
                        'work_order_id' => $workOrder->id,
                        'text' => $comment['text'] ?? null, // Allow null text
                        'parent_id' => null, // Temporary null, will update later
                        'user_id' => auth()->id(),
                    ]);

                    // Map the old comment ID to the new comment ID
                    $commentIdMap[$comment['commentId']] = $newComment->id;
                }

                // Second pass: Update parent IDs and save files
                foreach ($comments as $comment) {
                    $newCommentId = $commentIdMap[$comment['commentId']];

                    if (!empty($comment['parentId'])) { 
                        $newParentId = $commentIdMap[$comment['parentId']];
                        WOComments::where('id', $newCommentId)->update(['parent_id' => $newParentId]);
                    }

                    // Save files associated with the comment
                    if (isset($comment['files']) && is_array($comment['files'])) { 
                        foreach ($comment['files'] as $file) {
                            // Check if the filename exceeds 50 characters
                            $originalFileName = $file['name'];
                            if (strlen($originalFileName) > 50) {
                                // Extract file extension
                                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                                // Truncate the filename and append a unique identifier
                                $baseName = pathinfo($originalFileName, PATHINFO_FILENAME);
                                $truncatedName = substr($baseName, 0, 30); // Truncate to 30 characters

                                // Append a unique identifier
                                $uniqueIdentifier = uniqid(); // You can use time() or any other unique string generator
                                $newFileName = $truncatedName . '_' . $uniqueIdentifier . '.' . $extension;
                            } else {
                                // Use the original filename if it's within the length limit
                                $newFileName = $originalFileName;
                            }

                            // Save the file data (image or PDF) to the database
                            CommentFile::create([
                                'comment_id' => $newCommentId,
                                'file_name' => $newFileName,
                                'file_data' => $file['src'], // This stores the base64 data
                            ]);
                        }
                    }
                }
            }
            if(isset($request->deposit_received_as) && $request->deposit_received_as != '') {
                $canCreateFinanceApproval = true;
            }
            if($canCreateFinanceApproval == true) {
                WOApprovals::create([
                    'work_order_id' => $workOrder->id,
                    'type' => 'finance', 
                    'status' => 'pending', 
                    'action_at' =>NULL,
                ]);
            }
            if($canCreateCOOApproval == true) {
                WOApprovals::create([
                    'work_order_id' => $workOrder->id,
                    'type' => 'coo', 
                    'status' => 'pending', 
                    'action_at' =>NULL,
                ]);
            }
            (new UserActivityController)->createActivity('Create '.$request->type.' work order');
            // Prepare the from details
            $template['from'] = 'no-reply@milele.com';
            $template['from_name'] = 'Milele Matrix';

            // Handle cases where customer_name is null
            $customerName = $workOrder->customer_name ?? 'Unknown Customer';

            // Prepare email data
            $subject = "New Work order " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;

            // Define a quick access link (adjust the route as needed)
            $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;

            // Retrieve and validate email addresses from .env
            $financeEmail = filter_var(env('FINANCE_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';

            // Check if any email is invalid and handle the error
            if (!$financeEmail || !$managementEmail || !$operationsEmail) {
                \Log::error('Invalid email addresses provided:', [
                    'financeEmail' => env('FINANCE_TEAM_EMAIL'),
                    'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
                    'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                ]);
                throw new \Exception('One or more email addresses are invalid.');
            }

            // Send email using a Blade template
            Mail::send('work_order.emails.new_wo', ['workOrder' => $workOrder, 'accessLink' => $accessLink], function ($message) use ($subject, $financeEmail, $managementEmail, $operationsEmail, $template) {
                $message->from($template['from'], $template['from_name'])
                        ->to([$financeEmail, $managementEmail, $operationsEmail])
                        ->subject($subject);
            });

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
        $canCreateFinanceApproval = true;
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
        return $canCreateFinanceApproval;
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
        $authId = Auth::id();
        $type = $workOrder->type;
    
        // Store permission checks to avoid redundant calls
        $hasFullAccess = Auth::user()->hasPermissionForSelectedRole([
            'export-exw-wo-details', 'export-cnf-wo-details', 'local-sale-wo-details'
        ]);
    
        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'current-user-export-exw-wo-details', 'current-user-export-cnf-wo-details', 'current-user-local-sale-wo-details'
        ]);
    
        // Build the base query with necessary relationships
        $query = WorkOrder::where('id', $workOrder->id)
            ->with([
                'comments',
                'financePendingApproval',
                'cooPendingApproval'
            ]);
    
        // Adjust the query based on user permissions
        if ($hasLimitedAccess) {
            $query->where('created_by', $authId);
        }
    
        try {
            // Fetch the current work order
            $workOrder = $query->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $errorMsg = "Sorry! You don't have permission to access this page.";
            return view('hrm.notaccess', compact('errorMsg'));
        }
        
        // Retrieve previous and next work order IDs
        $previous = WorkOrder::where('type', $type)
            ->where('id', '<', $workOrder->id)
            ->when($hasLimitedAccess, function($query) use ($authId) {
                return $query->where('created_by', $authId);
            })
            ->max('id');
    
        $next = WorkOrder::where('type', $type)
            ->where('id', '>', $workOrder->id)
            ->when($hasLimitedAccess, function($query) use ($authId) {
                return $query->where('created_by', $authId);
            })
            ->min('id');
    
        // Get active users excluding specific IDs
        $users = User::where('status', 'active')
            ->whereNotIn('id', [1, 16])
            ->whereHas('empProfile', function($q) {
                $q->where('type', 'employee');
            })
            ->orderBy('name', 'ASC')
            ->get();
    
        return view('work_order.export_exw.show', compact('type', 'users', 'workOrder', 'previous', 'next'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        $previous = $next = '';
        $authId = Auth::id();
        // Store permission checks
        $hasFullAccess = Auth::user()->hasPermissionForSelectedRole([
            'edit-all-export-exw-work-order', 'edit-all-export-cnf-work-order', 'edit-all-local-sale-work-order'
        ]);

        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'edit-current-user-export-exw-work-order', 'edit-current-user-export-cnf-work-order', 'edit-current-user-local-sale-work-order'
        ]);
        $type = $workOrder->type;
        // Build the query to retrieve the work order
        $workOrderQuery = WorkOrder::where('id', $workOrder->id)
        ->with('vehicles.addons', 'comments', 'financePendingApproval', 'cooPendingApproval');

        // Apply the created_by condition if the user has limited access
        if ($hasLimitedAccess) {
        $workOrderQuery->where('created_by', $authId);
        }

         try {
             // Execute the query to get the work order
            $workOrder = $workOrderQuery->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $errorMsg = "Sorry! You don't have permission to access this page.";
            return view('hrm.notaccess', compact('errorMsg'));
        }
          // Retrieve previous and next work order IDs
          $previous = WorkOrder::where('type', $type)
          ->where('id', '<', $workOrder->id)
          ->when($hasLimitedAccess, function($query) use ($authId) {
              return $query->where('created_by', $authId);
          })
          ->max('id');
  
      $next = WorkOrder::where('type', $type)
          ->where('id', '>', $workOrder->id)
          ->when($hasLimitedAccess, function($query) use ($authId) {
              return $query->where('created_by', $authId);
          })
          ->min('id');

        // Select data from the WorkOrder table
        $workOrders = WorkOrder::select(
            DB::raw('TRIM(customer_name) as customer_name'), 
            'customer_email', 
            'customer_company_number', 
            'customer_address',
            DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\WorkOrder' as reference_type"),
            DB::raw('NULL as country_id'), // Assuming WorkOrder does not have is_demand_planning_customer field
            DB::raw('NULL as is_demand_planning_customer'),
            DB::raw("CONCAT(TRIM(customer_name), '_', IFNULL(customer_email, ''), '_', IFNULL(customer_company_number, '')) as unique_id")
        );

        // Select and transform data from the Clients table
        $clients = Clients::select(
            DB::raw('TRIM(name) as customer_name'), 
            DB::raw('email as customer_email'), 
            DB::raw('phone as customer_company_number'), 
            DB::raw('NULL as customer_address'),
            DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\Clients' as reference_type"),
            'country_id',
            'is_demand_planning_customer',
            DB::raw("CONCAT(TRIM(name), '_', IFNULL(email, ''), '_', IFNULL(phone, ''), '_', IFNULL(country_id, '')) as unique_id")
        );
        // Apply the permission-based condition
        if ($hasLimitedAccess) {
            // Add the created_by condition for limited access
            $workOrders->where('created_by', $authId);
            $clients->where('created_by', $authId);
        }
        // Combine the results using union
        $combinedResults = $workOrders
            ->union($clients)
            ->get();

        // Clean up customer names in PHP
        $combinedResults = $combinedResults->map(function ($item) {
            // Replace multiple spaces with a single space
            $item->customer_name = preg_replace('/\s+/', ' ', trim($item->customer_name));
            return $item;
        });

        // Process combined results to remove duplicates based on unique_id
        $customers = $combinedResults->groupBy('unique_id')->map(function($items) {
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
        $vins = Vehicles::orderBy('vin','ASC')->whereNotNull('vin')->with('variant.master_model_lines.brand','interior','exterior','warehouseLocation','document')->get()->unique('vin')
        ->values();
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
        return view('work_order.export_exw.create',compact('previous','next','workOrder','customerCount','type','customers','airlines','vins','users','addons','charges'))->with([
            'vinsJson' => $vins->toJson(), // Single encoding here
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    { 
        DB::beginTransaction();
        try { 
            $canCreateFinanceApproval = false;
            $canCreateCOOApproval = false;
            if(isset($request->deposit_received_as) && $request->deposit_received_as != '' && ($request->deposit_received_as != $workOrder->deposit_received_as)) {
                $canCreateFinanceApproval = true;
            }
            $authId = Auth::id();            
            $newComment = WOComments::create([
                'work_order_id' => $workOrder->id,
                'text' => "The work order data was changed as follows by ".auth()->user()->name, // Allow null text
                'parent_id' => null, // Temporary null, will update later
                'user_id' => null,
            ]);
            $CommentId = $newComment->id;
            $canDeleteComment = true;
            // Initialize newData array
            $newData = [];
            $newData = $request->all();
            if(isset($request->is_batch)) {
                $newData['is_batch'] = 1;
                $newData['batch'] = $request->batch;
            }
            else {
                $newData['is_batch'] = 0;
                $newData['batch'] = NULL;
            }
            // Extract full values for specific nested fields
            $nestedFields = [
                'customer_company_number',
                'customer_representative_contact',
                'delivery_contact_person_number',
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
            function handleFileUpload($request, $fileKey, $path, &$newData, $workOrder, $oldData, $deleteFlag = null, $CommentId) {
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

                if ($oldValue != $newValue && $newValue != NULL) {
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
                        'comment_id' => $CommentId,
                    ]);
                    $canDeleteComment = false;
                }
            }

            // Prepare old data for comparison
            $oldData = $workOrder->getOriginal();

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
                handleFileUpload($request, $fileKey, $path, $newData, $workOrder, $oldData, 'is_' . $fileKey . '_delete',$CommentId);
            }
    
            // List of fields to exclude
            $excludeFields = [
                '_method', '_token', 'customerCount', 'type', 'customer_type', 'comments', 'currency', 'wo_id', 'updated_by',
                'brn_file', 'signed_pfi', 'signed_contract', 'payment_receipts', 'noc', 'enduser_trade_license',
                'enduser_passport', 'enduser_contract', 'vehicle_handover_person_id', 'new_customer_name', 'existing_customer_name','customer_name','customer_reference_id',
                'is_brn_file_delete','is_signed_pfi_delete','is_signed_contract_delete','is_payment_receipts_delete','is_noc_delete',
                'is_enduser_trade_license_delete','is_enduser_passport_delete','is_enduser_contract_delete','is_vehicle_handover_person_id_delete',
                'vin_multiple',
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
                        'changed_at' => Carbon::now(),
                        'comment_id' => $CommentId,
                    ];
                    $canDeleteComment = false;
                    // Check for specified fields and create an entry in the WOApprovals model
                    if (in_array($field, ['so_total_amount','so_vehicle_quantity', 'amount_received', 'balance_amount'])) {
                        $canCreateFinanceApproval = true;
                    }
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
                    'changed_at' => Carbon::now(),
                    'comment_id' =>$CommentId,
                ];
                $canDeleteComment = false;
            } else if ($request->customer_type == 'existing' && $oldData['customer_name'] != $request->existing_customer_name) {
                $changeType = 'Change';
                if (is_null($oldData['customer_name']) && !is_null($request->existing_customer_name)) {
                    $changeType = 'Set';
                } else if (!is_null($oldData['customer_name']) && is_null($request->existing_customer_name)) {
                    $changeType = 'Unset';
                }
                $changes[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => $oldData['customer_name'],
                    'new_value' => $request->existing_customer_name,
                    'type' => $changeType,
                    'changed_at' => Carbon::now(),
                    'comment_id' => $CommentId,
                ];
                $canDeleteComment = false;
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
                    'changed_at' => Carbon::now(),
                    'comment_id' =>$CommentId,
                ];
                $canDeleteComment = false;
                $canCreateFinanceApproval = true;
            }
            // If there are changes, insert them into the WORecordHistory
            if (!empty($changes)) { 
                WORecordHistory::insert($changes);
            }
            $workOrder['updated_by'] = $authId;
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
            $NewVehicleIdArr = [];
            foreach ($vehiclesData as $vehicleData) {
                $id = $vehicleData['id'];
                // Define the fields to exclude
                $excludeVehicleFields = [
                    'id','work_order_id','vehicle_id','updated_by','created_by','comment_id'
                ];
               
                // Update if exists, otherwise create
                if (isset($existingVehicles[$id])) {                    
                    // Mark this VIN as processed
                    $processedIds[] = $id; 
                    $vehicle = $existingVehicles[$id];

                    $createVehComMap = [];
                    $createVehComMap['type'] = 'update';
                    $createVehComMap['comment_id'] = $CommentId;
                    $createVehComMap['vehicle_id'] = $vehicle->id;
                    $createVehComMap['wo_id'] = $workOrder->id;
                    $CreatedVehComMap = CommentVehicleMapping::create($createVehComMap);
                    $canDeleteComment = false;
                    $canDeleteCreatedVehComMap = true;

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
                                'comment_vehicle_id' => $CreatedVehComMap->id,
                            ]);
                            $canDeleteCreatedVehComMap = false;
                            $canCreateCOOApproval =true;
                        }
                    }
                    $vehicle->updated_by = $authId;
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

                            $createCommVehAddon['type'] = 'update';
                            $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                            $createCommVehAddon['addon_id'] = $addon->id;
                            $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                            $canDeleteCreateVehComAddMap = true;
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
                                        'cvm_id' => $createdCommVehAddonMapp->id,
                                    ]);
                                    $canCreateFinanceApproval = true;
                                    $canDeleteCreateVehComAddMap = false;
                                }
                            }
                            if($canDeleteCreateVehComAddMap == true) {
                                $deleteCreateVehComAddMap = CommentVehicleAddonMapping::where('id',$createdCommVehAddonMapp->id)->first();
                                if($deleteCreateVehComAddMap) {
                                    $deleteCreateVehComAddMap->delete();
                                }
                            }
                            else {
                                $canDeleteCreatedVehComMap = false;

                            }
                            // Save the vehicle with updated data
                            $addon->save();
                        } else {
                            $addonData['w_o_vehicle_id'] = $vehicle->id;
                            $addonData['created_by'] = Auth::id();
                            $addonData['comment_id'] = $CommentId;
                            $woVehicleAddon = WOVehicleAddons::create($addonData);
                            $canCreateFinanceApproval = true;
                            $createCommVehAddon['type'] = 'store';
                            $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                            $createCommVehAddon['addon_id'] = $woVehicleAddon->id;
                            $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                            $canDeleteCreatedVehComMap = false;
                            $canDeleteCreateVehComAddMap = true;
                            $canDeleteComment = false;
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
                                    'cvm_id' => $createdCommVehAddonMapp->id,
                                ]);
                                $canDeleteCreateVehComAddMap = false;
                            }
                            if($canDeleteCreateVehComAddMap == true) {
                                $deleteCreateVehComAddMap = CommentVehicleAddonMapping::where('id',$createdCommVehAddonMapp->id)->first();
                                if($deleteCreateVehComAddMap) {
                                    $deleteCreateVehComAddMap->delete();
                                }
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
                        $createCommVehAddon['type'] = 'delete';
                        $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                        $createCommVehAddon['addon_id'] = $addon->id;
                        $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                        $addon->deleted_by = Auth::id();
                        $addon->delete_cvm_id = $CreatedVehComMap->id;
                        $addon->save();
                        $canDeleteCreatedVehComMap = false;
                    }

                    // Now delete the addons
                    WOVehicleAddons::whereNotIn('id', $processedAddonIds)->where('w_o_vehicle_id', $vehicle->id)->delete();
                    if($canDeleteCreatedVehComMap == true) {
                        $deleteCommVehMap = CommentVehicleMapping::where('id',$CreatedVehComMap->id)->first();
                        if($deleteCommVehMap) {
                            $deleteCommVehMap->delete();
                        }
                        $canDeleteComment = true;
                    }
                    // ADDON END..............................
                } else {
                    $vehicleData['work_order_id'] = $workOrder->id;
                    $vehicleData['created_by'] = Auth::id();
                    $vehicleData['comment_id'] = $CommentId;
                    $canDeleteComment = false;
                    $woVehicles = WOVehicles::create($vehicleData);
                    $canCreateCOOApproval = true;
                    $createVehComMap = [];
                    $createVehComMap['type'] = 'store';
                    $createVehComMap['comment_id'] = $CommentId;
                    $createVehComMap['vehicle_id'] = $woVehicles->id;
                    $createVehComMap['wo_id'] = $workOrder->id;
                    $CreatedVehComMap = CommentVehicleMapping::create($createVehComMap);
                    $canDeleteComment = false;
                    $canDeleteCreatedVehComMap = true;

                    // Push the newly created vehicle's ID into the array
                    $NewVehicleIdArr[] = $woVehicles->id;
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
                            'comment_vehicle_id' =>$CreatedVehComMap->id,
                        ]);
                        $canDeleteCreatedVehComMap = false;
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
                                    $canCreateFinanceApproval = true;
                                    $createCommVehAddon['type'] = 'store';
                                    $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                                    $createCommVehAddon['addon_id'] = $WOVehicleAddons->id;
                                    $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                                    $canDeleteCreatedVehComMap = false;
                                    $canDeleteCreateVehComAddMap = true;
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
                                            'cvm_id' =>$createdCommVehAddonMapp->id,
                                        ]);
                                        $canDeleteCreateVehComAddMap = false;
                                    }
                                    // Mark this addon as processed
                                    if($canDeleteCreateVehComAddMap == true) {
                                        $deleteCreateVehComAddMap = CommentVehicleAddonMapping::where('id',$createdCommVehAddonMapp->id)->first();
                                        if($deleteCreateVehComAddMap) {
                                            $deleteCreateVehComAddMap->delete();
                                        }
                                    }
                                }
                            }
                        }
                    }  
                    // ADDON END ..................  
                    $processedIds[] = $woVehicles->id;
                    if($canDeleteCreatedVehComMap == true) {
                        $deleteCommVehMap = CommentVehicleMapping::where('id',$CreatedVehComMap->id)->first();
                        if($deleteCommVehMap) {
                            $deleteCommVehMap->delete();
                        }
                        $canDeleteComment = true;
                    }
                }
            }
           // Ensure $processedIds only contains valid IDs
            $processedIds = array_filter($processedIds, function($id) {
                return !is_null($id);
            });

            $vehiclesToDelete = WOVehicles::whereNotIn('id', $processedIds)->where('work_order_id', $workOrder->id)->get();
            foreach ($vehiclesToDelete as $vehicle) {
                $vehicle->deleted_by = Auth::id();
                $vehicle->deleted_comment_id = $CommentId;
                $vehicle->save();
                $canDeleteComment = false;
                $canCreateCOOApproval = true;
            }

            // Now delete the vehicles
            WOVehicles::whereNotIn('id', $processedIds)->where('work_order_id',$workOrder->id)->delete();

            // VEHICLES END ........................................
            // BOE
            if (isset($request->boe) && count($request->boe) > 0) {
                // $createVehComMap['comment_id'] = $CommentId;
                // $createVehComMap['vehicle_id'] = $vehicle->id;
                // $CreatedVehComMap = CommentVehicleMapping::where('comment_id',$CommentId)
                DB::transaction(function() use ($request, $workOrder,$CommentId,$NewVehicleIdArr) {
                    // Step 1: Fetch all WOVehicles associated with the work order
                    $woVehiclesForBOE = WOVehicles::where('work_order_id', $workOrder->id)->get();
            
                    // Step 2: Create a list of all VINs provided in the request
                    $requestVins = [];
                    foreach ($request->boe as $boeNumber => $boe) {
                        if (isset($boe['vin']) && count($boe['vin']) > 0) {
                            foreach ($boe['vin'] as $vin) {
                                $requestVins[] = $vin;
                            }
                        }
                    }
            
                    // Step 3: Iterate through each WOVehicle and check if its VIN exists in the provided list
                    foreach ($woVehiclesForBOE as $woVehicle) {
                        if (!in_array($woVehicle->vin, $requestVins)) {
                            // Step 4: If a VIN does not exist in the list, update the boe_number to NULL and log the change
                            $oldBoeNumber = $woVehicle->boe_number;
                            if ($oldBoeNumber !== null) {
                                $woVehicle->boe_number = null;
                                $woVehicle->save();
                                // $CreatedVehComMap = CommentVehicleMapping::where('comment_id',$CommentId)->where('vehicle_id',$woVehicle->id)->first();
                                // if($CreatedVehComMap == null) {
                                //     $createVehComMap = [];
                                //     $createVehComMap['type'] = 'update';
                                //     $createVehComMap['comment_id'] = $CommentId;
                                //     $createVehComMap['vehicle_id'] = $vehicle->id;
                                //     $createVehComMap['wo_id'] = $workOrder->id;
                                //     $CreatedVehComMap = CommentVehicleMapping::create($createVehComMap);
                                //     $canDeleteComment = false;
                                //     $canDeleteCreatedVehComMap = true;
                                // }
                                // Create history record based on whether the vehicle ID exists in $NewVehicleIdArr
                                if (in_array($woVehicle->id, $NewVehicleIdArr)) {
                                    WOVehicleRecordHistory::create([
                                        'w_o_vehicle_id' => $woVehicle->id,
                                        'field_name' => 'boe_number',
                                        'old_value' => $oldBoeNumber,
                                        'new_value' => null,
                                        'type' => 'Unset',
                                        'user_id' => Auth::id(),
                                        'changed_at' => Carbon::now(),
                                        // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                                    ]);
                                    $canDeleteCreatedVehComMap = false;
                                } else {
                                    WOVehicleRecordHistory::create([
                                        'w_o_vehicle_id' => $woVehicle->id,
                                        'field_name' => 'boe_number',
                                        'old_value' => $oldBoeNumber,
                                        'new_value' => null,
                                        'type' => 'Unset',
                                        'user_id' => Auth::id(),
                                        'changed_at' => Carbon::now(),
                                        // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                                    ]);
                                    $canDeleteCreatedVehComMap = false;
                                }
                            }
                        }
                    }
            
                    // Existing logic to update the boe_number for the provided VINs
                    foreach ($request->boe as $boeNumber => $boe) {
                        if (isset($boe['vin']) && count($boe['vin']) > 0) {
                            foreach ($boe['vin'] as $vin) {
                                $vinUpdate = WOVehicles::where('vin', $vin)->where('work_order_id', $workOrder->id)->first();
                                if ($vinUpdate && $vinUpdate->boe_number != $boeNumber) {
                                    $oldBoeNumber = $vinUpdate->boe_number;
                                    $vinUpdate->boe_number = $boeNumber;
                                    $vinUpdate->save();
            
                                    // Determine the change type
                                    $changeType = 'Change';
                                    if (is_null($oldBoeNumber) && !is_null($boeNumber)) {
                                        $changeType = 'Set';
                                    } elseif (!is_null($oldBoeNumber) && is_null($boeNumber)) {
                                        $changeType = 'Unset';
                                    }
                                    if (in_array($vinUpdate->id, $NewVehicleIdArr)) {
                                        // Create history record
                                        WOVehicleRecordHistory::create([
                                            'w_o_vehicle_id' => $vinUpdate->id,
                                            'field_name' => 'boe_number',
                                            'old_value' => $oldBoeNumber,
                                            'new_value' => $boeNumber,
                                            'type' => $changeType,
                                            'user_id' => Auth::id(),
                                            'changed_at' => Carbon::now(),
                                            // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                                        ]);
                                        $canDeleteCreatedVehComMap = false;
                                    }
                                    else {
                                        WOVehicleRecordHistory::create([
                                            'w_o_vehicle_id' => $vinUpdate->id,
                                            'field_name' => 'boe_number',
                                            'old_value' => $oldBoeNumber,
                                            'new_value' => $boeNumber,
                                            'type' => $changeType,
                                            'user_id' => Auth::id(),
                                            'changed_at' => Carbon::now(),
                                            // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                                        ]);
                                        $canDeleteCreatedVehComMap = false;
                                    }
                                }
                            }
                        }
                    }
                });
            }
                     

            // Deposit against vehicles
            if (isset($request->deposit_received_as) && $request->deposit_received_as === 'custom_deposit') {
                DB::transaction(function() use ($request, $workOrder,$CommentId,$NewVehicleIdArr) {
                    // Fetch all WOVehicles associated with the work order
                    $woVehicles = WOVehicles::where('work_order_id', $workOrder->id)->get();
            
                    // Create a list of all VINs provided in the request
                    $requestVins = isset($request->deposit_aganist_vehicle) && is_array($request->deposit_aganist_vehicle) ? $request->deposit_aganist_vehicle : [];
            
                    // Iterate through each WOVehicle
                    foreach ($woVehicles as $woVehicle) {
                        if (!in_array($woVehicle->vin, $requestVins)) {
                            // Update deposit_received to 'no' if VIN does not exist in the request list
                            if ($woVehicle->deposit_received != 'no') {
                                $oldDepositReceived = $woVehicle->deposit_received;
                                $woVehicle->deposit_received = 'no';
                                $woVehicle->save();
                                if (in_array($woVehicle->id, $NewVehicleIdArr)) {
                                    // Create history record
                                    WOVehicleRecordHistory::create([
                                        'w_o_vehicle_id' => $woVehicle->id,
                                        'field_name' => 'deposit_received',
                                        'old_value' => $oldDepositReceived,
                                        'new_value' => 'no',
                                        'type' => 'Change',
                                        'user_id' => Auth::id(),
                                        'changed_at' => Carbon::now(),
                                        // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                                    ]);
                                    $canDeleteCreatedVehComMap = false;
                                    $canCreateFinanceApproval = true;
                                }
                                else {
                                    // dd('9');
                                    // Create history record
                                    WOVehicleRecordHistory::create([
                                        'w_o_vehicle_id' => $woVehicle->id,
                                        'field_name' => 'deposit_received',
                                        'old_value' => $oldDepositReceived,
                                        'new_value' => 'no',
                                        'type' => 'Change',
                                        'user_id' => Auth::id(),
                                        'changed_at' => Carbon::now(),
                                        // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                                    ]);
                                    $canDeleteCreatedVehComMap = false;
                                    $canCreateFinanceApproval = true;
                                }
                            }
                        } else {
                            // If the VIN exists in the request list, set deposit_received to 'yes'
                            $vinUpdate = WOVehicles::where('vin', $woVehicle->vin)->where('work_order_id', $workOrder->id)->first();
                            if ($vinUpdate && $vinUpdate->deposit_received != 'yes') {
                                $oldDepositReceived = $vinUpdate->deposit_received;
                                $vinUpdate->deposit_received = 'yes';
                                $vinUpdate->save();
                                if (in_array($vinUpdate->id, $NewVehicleIdArr)) {
                                    // Create history record
                                    WOVehicleRecordHistory::create([
                                        'w_o_vehicle_id' => $vinUpdate->id,
                                        'field_name' => 'deposit_received',
                                        'old_value' => $oldDepositReceived,
                                        'new_value' => 'yes',
                                        'type' => 'Change',
                                        'user_id' => Auth::id(),
                                        'changed_at' => Carbon::now(),
                                    //    'comment_vehicle_id' =>$CreatedVehComMap->id,
                                    ]);
                                    $canDeleteCreatedVehComMap = false;
                                    $canCreateFinanceApproval = true;
                                }
                                else {
                                    // Create history record
                                    WOVehicleRecordHistory::create([
                                        'w_o_vehicle_id' => $vinUpdate->id,
                                        'field_name' => 'deposit_received',
                                        'old_value' => $oldDepositReceived,
                                        'new_value' => 'yes',
                                        'type' => 'Change',
                                        'user_id' => Auth::id(),
                                        'changed_at' => Carbon::now(),
                                        // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                                    ]);
                                    $canDeleteCreatedVehComMap = false;
                                    $canCreateFinanceApproval = true;
                                }
                            }
                        }
                    }
                });
            }
            else if ((isset($request->deposit_received_as) && $request->deposit_received_as === 'custom_deposit') OR 
            (isset($request->deposit_received_as) && $request->deposit_received_as === null) OR !isset($request->deposit_received_as)) {
                $woVehicles = WOVehicles::where('work_order_id', $workOrder->id)->get();
                foreach ($woVehicles as $woVehicle) {
                    if($woVehicle->deposit_received == 'yes') {
                        if (in_array($woVehicle->id, $NewVehicleIdArr)) {
                            // Create history record
                            WOVehicleRecordHistory::create([
                                'w_o_vehicle_id' => $woVehicle->id,
                                'field_name' => 'deposit_received',
                                'old_value' => $oldDepositReceived,
                                'new_value' => 'no',
                                'type' => 'Change',
                                'user_id' => Auth::id(),
                                'changed_at' => Carbon::now(),
                                // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                            ]);
                            $canDeleteCreatedVehComMap = false;
                            $canCreateFinanceApproval = true;
                        }
                        else {
                            // Create history record
                            WOVehicleRecordHistory::create([
                                'w_o_vehicle_id' => $woVehicle->id,
                                'field_name' => 'deposit_received',
                                'old_value' => $oldDepositReceived,
                                'new_value' => 'no',
                                'type' => 'Change',
                                'user_id' => Auth::id(),
                                'changed_at' => Carbon::now(),
                                // 'comment_vehicle_id' =>$CreatedVehComMap->id,
                            ]);
                            $canDeleteCreatedVehComMap = false;
                            $canCreateFinanceApproval = true;
                        }
                    }
                    $woVehicle->deposit_received = 'no';
                    $woVehicle->save();
                }
            }    

            if($canDeleteComment == true) {
                $deleteComment = WOComments::where('id', $CommentId)->first();
                if($deleteComment) {
                    $deleteComment->delete();
                }
            }
            if($canCreateFinanceApproval == true) {
                $financePendingApproval = WOApprovals::where('work_order_id',$workOrder->id)->where('type','finance')->where('status','pending')->first();
                if($financePendingApproval == null) { 
                    WOApprovals::create([
                        'work_order_id' => $workOrder->id,
                        'type' => 'finance', 
                        'status' => 'pending', 
                        'action_at' =>NULL,
                    ]);
                }
                else {
                    $financePendingApproval->updated_at = Carbon::now();
                    $financePendingApproval->update();
                }
            }
            if($canCreateCOOApproval == true) {
                $cooPendingApprovals = WOApprovals::where('work_order_id',$workOrder->id)->where('type','coo')->where('status','pending')->first();
                if($cooPendingApprovals == null) { 
                    WOApprovals::create([
                        'work_order_id' => $workOrder->id,
                        'type' => 'coo', 
                        'status' => 'pending', 
                        'action_at' =>NULL,
                    ]);
                }
                else {
                    $cooPendingApprovals->updated_at = Carbon::now();
                    $cooPendingApprovals->update();
                }
            }
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
        // Validate the request data, making 'text' nullable
        $request->validate([
            'text' => 'nullable|string|max:255', // 'text' is now nullable
            'parent_id' => 'nullable|integer|exists:w_o_comments,id',
            'work_order_id' => 'required|integer|exists:work_orders,id'
        ]);
    
        // Check if text is null and there are no files
        if (is_null($request->input('text')) && !$request->hasFile('files')) {
            return response()->json(['error' => 'Text or files are required.'], 422);
        }
    
        // Store empty space if text is null
        $text = $request->input('text') ?? '';
        // Create the comment with nullable text
        $comment = WOComments::create([
            'work_order_id' => $request->input('work_order_id'),
            'text' => $text, // Store text or empty space
            'parent_id' => $request->input('parent_id'),
            'user_id' => auth()->id(), // Assuming you're using Laravel's authentication
        ]);
        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileData = base64_encode(file_get_contents($file->getRealPath()));
                $files[] = [
                    'file_name' => $file->getClientOriginalName(),
                    'file_data' => 'data:' . $file->getMimeType() . ';base64,' . $fileData
                ];
            }
        }
    
        // Assuming you have a relation set up for files on the comment model
        if (!empty($files)) {
            $comment->files()->createMany($files);
        }
    
        // Respond with the comment and files data
        return response()->json($comment->load('files','user'), 201);
    }
    // public function getComments($workOrderId)
    // {
    //     // Fetch comments for the specified work order
    //     $comments = WOComments::where('work_order_id', $workOrderId)->get();
    //     return response()->json(['comments' => $comments]);
    // }
    
    public function getComments($workOrderId)
    {
        $comments = WOComments::where('work_order_id', $workOrderId)
            ->with('files', 'user', 'wo_histories', 'removed_vehicles','new_vehicles.vehicle.addonsWithTrashed',
            'updated_vehicles.vehicle.addonsWithTrashed','new_vehicles.recordHistories','updated_vehicles.recordHistories',
            'new_vehicles.storeMappingAddons.recordHistories','updated_vehicles.updateMappingAddons.recordHistories',
            'new_vehicles.storeMappingAddons.addon','updated_vehicles.updateMappingAddons.addon',
            'updated_vehicles.storeMappingAddons.recordHistories','updated_vehicles.storeMappingAddons.addon','updated_vehicles.deleteMappingAddons.addon',
            'updated_vehicles.updateMappingAddons.recordHistories','updated_vehicles.updateMappingAddons.addon'
            )
            // 'updated_vehicles.deleteMappingAddons'
            ->get();
        return response()->json(['comments' => $comments]);
    }
    public function uniqueWO(Request $request) { 
        $validator = Validator::make($request->all(), [
            'wo_number' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            try {
                $wo = WorkOrder::where('wo_number',$request->wo_number);
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
        $woVehicle = WOVehicles::withTrashed()->where('id', $id)->first();
        $datas = WOVehicleRecordHistory::where('w_o_vehicle_id',$id)->get();
        return view('work_order.export_exw.show_vehicle_history',compact('datas','woVehicle'));
    }
    public function vehicleAddonDataHistory($id) {
        $woVehicleAddon = WOVehicleAddons::where('id',$id)->first();
        $datas = WOVehicleAddonRecordHistory::where('w_o_vehicle_addon_id',$id)->get();
        return view('work_order.export_exw.show_vehicle_addon_history',compact('datas','woVehicleAddon'));
    }
    public function financeApproval(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $woApprovals = WOApprovals::where('id', $request->id)->first();
                $workOrder = WorkOrder::where('id',$woApprovals->work_order_id)->first();             
    
                if ($woApprovals && $woApprovals->action_at == '' && $woApprovals->status == 'pending') {
                    $woApprovals->action_at = Carbon::now();
                    $woApprovals->user_id = $authId;
                    $woApprovals->comments = $request->comments;
    
                    $woApprovals->status = ($request->status == 'approve') ? 'approved' : 'rejected';
                    $woApprovals->update();
    
                    $fields = ['so_total_amount', 'currency', 'so_vehicle_quantity', 'amount_received', 'balance_amount','deposit_received_as'];
    
                    $woHistory = WORecordHistory::where('work_order_id', $woApprovals->work_order_id)
                        ->whereIn('field_name', $fields)
                        ->orderBy('changed_at', 'desc')
                        ->get()
                        ->unique('field_name');
    
                    foreach ($woHistory as $record) {
                        WOApprovalDataHistory::create([
                            'w_o_approvals_id' => $woApprovals->id,
                            'wo_history_id' => $record->id
                        ]);
                    }
                    if($workOrder->deposit_received_as == 'custom_deposit') {
                        $depAgnVeh = WOVehicles::where('work_order_id', $woApprovals->work_order_id)->where('deposit_received','yes')->select('id')->get();
                        foreach($depAgnVeh as $depAgnVehId) {                      
                            WOApprovalDepositAganistVehicle::create([
                                'w_o_approvals_id' => $woApprovals->id,
                                'w_o_vehicle_id' => $depAgnVehId->id
                            ]);
                        }
                    }
    
                    $woVehicleIds = WOVehicles::where('work_order_id', $woApprovals->work_order_id)->pluck('id');
                    $woAddonIds = WOVehicleAddons::whereIn('w_o_vehicle_id', $woVehicleIds)->pluck('id');
    
                    $woAddonHistory = WOVehicleAddonRecordHistory::whereIn('w_o_vehicle_addon_id', $woAddonIds)
                        ->orderBy('changed_at', 'desc')
                        ->get()
                        ->unique(function ($item) {
                            return $item['field_name'] . $item['w_o_vehicle_addon_id'];
                        });
    
                    foreach ($woAddonHistory as $record) {
                        WOApprovalAddonDataHistory::create([
                            'w_o_approvals_id' => $woApprovals->id,
                            'wo_addon_history_id' => $record->id
                        ]);
                    }
    
                    DB::commit();
                    // Send email notification
                    $this->sendFinanceApprovalEmail($workOrder, $woApprovals->status, $woApprovals->comments,$woApprovals->user->name);
                    return response()->json('success');
                } else if ($woApprovals && $woApprovals->action_at != '') {
                    DB::commit();
                    return response()->json('error');
                }
            } catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg = "Something went wrong! Contact your admin";
                return view('hrm.notaccess', compact('errorMsg'));
            }
        }
    }
    private function sendFinanceApprovalEmail($workOrder, $status, $comments, $userName)
    {
        // Prepare the from details
        $template['from'] = 'no-reply@milele.com';
        $template['from_name'] = 'Milele Matrix';

        // Handle cases where customer_name is null
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';

        // Prepare email subject
        $subject = "WO Finance Approval '{$status}' " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;

        // Define a quick access link (adjust the route as needed)
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $approvalHistoryLink = env('BASE_URL') . '/finance-approval-history/' . $workOrder->id;
        // Retrieve and validate email addresses from .env
        $financeEmail = filter_var(env('FINANCE_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';

        // Retrieve the CreatedBy user's email and validate it
        $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);

        // Check if any email is invalid and handle the error
        if (!$financeEmail || !$managementEmail || !$operationsEmail || !$createdByEmail) {
            \Log::error('Invalid email addresses provided:', [
                'financeEmail' => env('FINANCE_TEAM_EMAIL'),
                'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
                'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                'createdByEmail' => $workOrder->CreatedBy->email ?? 'null'
            ]);
            throw new \Exception('One or more email addresses are invalid.');
        }

        // Send email using a Blade template
        Mail::send('work_order.emails.fin_approval', [
            'workOrder' => $workOrder,
            'accessLink' => $accessLink,
            'approvalHistoryLink' => $approvalHistoryLink,
            'comments' => $comments,
            'userName' => $userName,
            'status' => $status
        ], function ($message) use ($subject, $financeEmail, $managementEmail, $operationsEmail, $createdByEmail, $template) {
            $message->from($template['from'], $template['from_name'])
                    ->to([$financeEmail, $managementEmail, $operationsEmail, $createdByEmail])
                    ->subject($subject);
        });
    }
    public function coeOfficeApproval(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $woApprovals = WOApprovals::where('id', $request->id)->first();
                $workOrder = WorkOrder::where('id',$woApprovals->work_order_id)->first(); 
                if ($woApprovals && $woApprovals->action_at == '' && $woApprovals->status == 'pending') {
                    $woApprovals->action_at = Carbon::now();
                    $woApprovals->user_id = $authId;
                    $woApprovals->comments = $request->comments;
    
                    $woApprovals->status = ($request->status == 'approve') ? 'approved' : 'rejected';
                    $woApprovals->update();
                    $woVehicleIds = WOVehicles::where('work_order_id', $woApprovals->work_order_id)->pluck('id');

                    $woVehicleHistory = WOVehicleRecordHistory::whereIn('w_o_vehicle_id', $woVehicleIds)
                        ->orderBy('changed_at', 'desc')
                        ->get()
                        ->unique(function ($item) {
                            return $item['field_name'] . $item['w_o_vehicle_id'];
                        });
                    foreach ($woVehicleHistory as $record) {
                        WOApprovalVehicleDataHistory::create([
                            'w_o_approvals_id' => $woApprovals->id,
                            'wo_vehicle_history_id' => $record->id
                        ]);
                    }
    
                    DB::commit();
                    // Send email notification
                    $this->sendCOOApprovalEmail($workOrder, $woApprovals->status, $woApprovals->comments,$woApprovals->user->name);                   
                    return response()->json('success');
                } else if ($woApprovals && $woApprovals->action_at != '') {
                    DB::commit();
                    return response()->json('error');
                }
            }
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    private function sendCOOApprovalEmail($workOrder, $status, $comments, $userName)
    {
        // Prepare the from details
        $template['from'] = 'no-reply@milele.com';
        $template['from_name'] = 'Milele Matrix';

        // Handle cases where customer_name is null
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';

        // Prepare email subject
        $subject = "WO COO Office Approval '{$status}' " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;

        // Define a quick access link (adjust the route as needed)
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $approvalHistoryLink = env('BASE_URL') . '/coo-approval-history/' . $workOrder->id;
        // Retrieve and validate email addresses from .env
        $financeEmail = filter_var(env('FINANCE_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';

        // Retrieve the CreatedBy user's email and validate it
        $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);

        // Check if any email is invalid and handle the error
        if (!$financeEmail || !$managementEmail || !$operationsEmail || !$createdByEmail) {
            \Log::error('Invalid email addresses provided:', [
                'financeEmail' => env('FINANCE_TEAM_EMAIL'),
                'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
                'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                'createdByEmail' => $workOrder->CreatedBy->email ?? 'null'
            ]);
            throw new \Exception('One or more email addresses are invalid.');
        }

        // Send email using a Blade template
        Mail::send('work_order.emails.coo_approval', [
            'workOrder' => $workOrder,
            'accessLink' => $accessLink,
            'approvalHistoryLink' => $approvalHistoryLink,
            'comments' => $comments,
            'userName' => $userName,
            'status' => $status
        ], function ($message) use ($subject, $financeEmail, $managementEmail, $operationsEmail, $createdByEmail, $template) {
            $message->from($template['from'], $template['from_name'])
                    ->to([$financeEmail, $managementEmail, $operationsEmail, $createdByEmail])
                    ->subject($subject);
        });
    }
}
