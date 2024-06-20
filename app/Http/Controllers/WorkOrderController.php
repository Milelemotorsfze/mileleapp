<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WOVehicles;
use App\Models\WOVehicleAddons;
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
        return view('work_order.export_exw.create',compact('type','customers','customerCount','airlines','vins','users'));
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
        // dd($request->all());
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

            // if($request->brn_file) {
            //     $brnFileName = auth()->id() . '_' . time() . '.'. $request->brn_file->extension();
            //     $type = $request->brn_file->getClientMimeType();
            //     $size = $request->brn_file->getSize();
            //     $request->brn_file->move(public_path('wo/brn_file'), $brnFileName);
            //     $input['brn_file'] = $brnFileName;
            // }
            // if($request->signed_pfi) {
            //     $signedPdfFileName = auth()->id() . '_' . time() . '.'. $request->signed_pfi->extension();
            //     $type = $request->signed_pfi->getClientMimeType();
            //     $size = $request->signed_pfi->getSize();
            //     $request->signed_pfi->move(public_path('wo/signed_pfi'), $signedPdfFileName);
            //     $input['signed_pfi'] = $signedPdfFileName;
            // }
            // if($request->signed_contract) {
            //     $signedContractFileName = auth()->id() . '_' . time() . '.'. $request->signed_contract->extension();
            //     $type = $request->signed_contract->getClientMimeType();
            //     $size = $request->signed_contract->getSize();
            //     $request->signed_contract->move(public_path('wo/signed_contract'), $signedContractFileName);
            //     $input['signed_contract'] = $signedContractFileName;
            // }
            // if($request->payment_receipts) {
            //     $paymentReceiptsFileName = auth()->id() . '_' . time() . '.'. $request->payment_receipts->extension();
            //     $type = $request->payment_receipts->getClientMimeType();
            //     $size = $request->payment_receipts->getSize();
            //     $request->payment_receipts->move(public_path('wo/payment_receipts'), $paymentReceiptsFileName);
            //     $input['payment_receipts'] = $paymentReceiptsFileName;
            // }
            // if($request->noc) {
            //     $nocFileName = auth()->id() . '_' . time() . '.'. $request->noc->extension();
            //     $type = $request->noc->getClientMimeType();
            //     $size = $request->noc->getSize();
            //     $request->noc->move(public_path('wo/noc'), $nocFileName);
            //     $input['noc'] = $nocFileName;
            // }
            // if($request->enduser_trade_license) {
            //     $enduserTradeLicenseFileName = auth()->id() . '_' . time() . '.'. $request->enduser_trade_license->extension();
            //     $type = $request->enduser_trade_license->getClientMimeType();
            //     $size = $request->enduser_trade_license->getSize();
            //     $request->enduser_trade_license->move(public_path('wo/enduser_trade_license'), $enduserTradeLicenseFileName);
            //     $input['enduser_trade_license'] = $enduserTradeLicenseFileName;
            // }
            // if($request->enduser_passport) {
            //     $enduserPassportFileName = auth()->id() . '_' . time() . '.'. $request->enduser_passport->extension();
            //     $type = $request->enduser_passport->getClientMimeType();
            //     $size = $request->enduser_passport->getSize();
            //     $request->enduser_passport->move(public_path('wo/enduser_passport'), $enduserPassportFileName);
            //     $input['enduser_passport'] = $enduserPassportFileName;
            // }
            // if($request->enduser_contract) {
            //     $enduserContractFileName = auth()->id() . '_' . time() . '.'. $request->enduser_contract->extension();
            //     $type = $request->enduser_contract->getClientMimeType();
            //     $size = $request->enduser_contract->getSize();
            //     $request->enduser_contract->move(public_path('wo/enduser_contract'), $enduserContractFileName);
            //     $input['enduser_contract'] = $enduserContractFileName;
            // }
            // if($request->vehicle_handover_person_id) {
            //     $vehicleHandoverPersonIdFileName = auth()->id() . '_' . time() . '.'. $request->vehicle_handover_person_id->extension();
            //     $type = $request->vehicle_handover_person_id->getClientMimeType();
            //     $size = $request->vehicle_handover_person_id->getSize();
            //     $request->vehicle_handover_person_id->move(public_path('wo/vehicle_handover_person_id'), $vehicleHandoverPersonIdFileName);
            //     $input['vehicle_handover_person_id'] = $vehicleHandoverPersonIdFileName;
            // } 
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
                'file_name' => $fileName,
                'file_type' => $file->getClientMimeType(),
                // 'file_size' => $file->getSize(),
                'file_path' => $path . '/' . $fileName
            ];
        }
    }
}
            $workOrder = WorkOrder::create($input);
            // Handle file uploads and save file paths
            // $fileFields = [
            //     'brn_file' => 'wo/brn_file',
            //     'signed_pfi' => 'wo/signed_pfi',
            //     'signed_contract' => 'wo/signed_contract',
            //     'payment_receipts' => 'wo/payment_receipts',
            //     'noc' => 'wo/noc',
            //     'enduser_trade_license' => 'wo/enduser_trade_license',
            //     'enduser_passport' => 'wo/enduser_passport',
            //     'enduser_contract' => 'wo/enduser_contract',
            //     'vehicle_handover_person_id' => 'wo/vehicle_handover_person_id'
            // ];
            // foreach ($fileFields as $fileField => $path) {
            //     if ($request->hasFile($fileField)) {
            //         $fileName = $this->handleFileUpload($request->file($fileField), $path);
            //         $validatedData[$fileField] = $fileName;
            //     }
            // }

            // Create the WorkOrder record
            // $workOrder = WorkOrder::create($validatedData);

            // Exclude specific fields and filter out non-NULL values and arrays
            $excludeFields = ['_token', 'customerCount', 'type', 'customer_type', 'comments','currency','wo_id',
                'brn_file','signed_pfi','signed_contract','payment_receipts','noc','enduser_trade_license','enduser_passport','enduser_contract',
                'vehicle_handover_person_id'];
            $nonNullData = array_filter($request->all(), function ($value, $key) use ($excludeFields) {
                return !is_null($value) && !is_array($value) && !in_array($key, $excludeFields);
            }, ARRAY_FILTER_USE_BOTH);

            // Additional specific nested fields to store if not null
            $nestedFields = [
                'customer_company_number' => 'full',
                'customer_representative_contact' => 'full',
                'freight_agent_contact_number' => 'full',
                'transporting_driver_contact_number' => 'full'
            ];

            // Store each non-NULL, non-array field in the data history
            foreach ($nonNullData as $field => $value) { 
                WORecordHistory::create([
                    'work_order_id' => $workOrder->id,
                    'field_name' => $field,
                    'old_value' => NULL,
                    'new_value' => $value,
                    'type' => 'SET',
                    'user_id' => Auth::id(),
                    'changed_at' => Carbon::now(),
                ]);
                if($field == 'so_amount') {
                    WORecordHistory::create([
                        'work_order_id' => $workOrder->id,
                        'field_name' => 'currency',
                        'old_value' => NULL,
                        'new_value' => $request->currency,
                        'type' => 'SET',
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
                        'type' => 'SET',
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ]);
                }
            }

            // Store file information in the data history table
// if (!empty($fileData)) {
//     foreach ($fileData as $data) {
//         // DataHistory::create([
//         //     'work_order_id' => $workOrder->id,
//         //     'file_name' => $data['file_name'],
//         //     'file_type' => $data['file_type'],
//         //     'file_size' => $data['file_size'],
//         //     'file_path' => $data['file_path']
//         // ]);
//         WORecordHistory::create([
//             'work_order_id' => $workOrder->id,
//             'field_name' => $data['file_name'],
//             'old_value' => NULL,
//             'new_value' => $data['file_path'],
//             'type' => 'SET',
//             'user_id' => Auth::id(),
//             'changed_at' => Carbon::now(),
//         ]);
//     }
// }

            // Store file paths in the data history
            // foreach ($fileFields as $fileField => $path) {
            //     if (isset($validatedData[$fileField])) {
            //         WORecordHistory::create([
            //             'work_order_id' => $workOrder->id,
            //             'field_name' => $fileField,
            //             'old_value' => NULL,
            //             'new_value' => $validatedData[$fileField],
            //             'type' => 'SET',
            //             'user_id' => Auth::id(),
            //             'changed_at' => Carbon::now(),
            //         ]);
            //     }
            // }

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
            // dd($nonNullData);
            return response()->json(['success' => true, 'message' => 'Work order created successfully.']);
            // return response()->json(['message' => 'Work order created successfully']);
            // return redirect()->route('work-order.index',$request->type)->with('success','Work Order created successfully!');
            // return response()->json(['message' => 'Work Order created successfully!', 'Work Order' => $workOrder], 201);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error creating Work Order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            // dd($nonNullData); dd($e);
            // return response()->json(['error' => 'Failed to create Work Order. Please try again.'], 500);
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
        return view('work_order.export_exw.create',compact('workOrder','customerCount','type','customers','airlines','vins','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, WorkOrder $workOrder)
    // {
    //     // dd($request->all());
    //     DB::beginTransaction();

    //     try {
    //     $authId = Auth::id();
    //     // $workOrder = WorkOrder::findOrFail($id);

    //     $newData = $request->all();

    //     $newData['customer_company_number'] = $request->customer_company_number['full'] ?? null;
    //     $newData['customer_representative_contact'] = $request->customer_representative_contact['full'] ?? null;
    //     $newData['freight_agent_contact_number'] = $request->freight_agent_contact_number['full'] ?? null;
    //     $newData['transporting_driver_contact_number'] = $request->transporting_driver_contact_number['full'] ?? null;
    //     $newData['updated_by'] = $authId;
    //     $newData['so_total_amount'] = $request->so_total_amount ?? 0.00;
    //     $newData['amount_received'] = $request->amount_received ?? 0.00;
    //     $newData['balance_amount'] = $request->balance_amount ?? 0.00;

    //     if ($request->customer_type == 'new') {
    //         $newData['customer_name'] = $request->new_customer_name;
    //     } else if ($request->customer_type == 'existing') {
    //         $newData['customer_name'] = $request->existing_customer_name;
    //     }

    //     $fields = [
    //         'air' => [
    //             'brn', 'container_number', 'shipping_line', 'forward_import_code',
    //             'trailer_number_plate', 'transportation_company',
    //             'transporting_driver_contact_number', 'transportation_company_details'
    //         ],
    //         'sea' => [
    //             'airline_reference_id', 'airline', 'airway_bill', 'trailer_number_plate',
    //             'transportation_company', 'transporting_driver_contact_number',
    //             'airway_details', 'transportation_company_details'
    //         ],
    //         'road' => [
    //             'brn_file', 'brn', 'container_number', 'airline_reference_id', 'airline',
    //             'airway_bill', 'shipping_line', 'airway_details', 'forward_import_code'
    //         ]
    //     ];
        
    //     $transportType = $request->transport_type;
        
    //     if (isset($fields[$transportType])) {
    //         foreach ($fields[$transportType] as $field) {
    //             $newData[$field] = NULL;
    //         }
    //     }

    //     $fileFields = [
    //         'brn_file' => 'wo/brn_file',
    //         'signed_pfi' => 'wo/signed_pfi',
    //         'signed_contract' => 'wo/signed_contract',
    //         'payment_receipts' => 'wo/payment_receipts',
    //         'noc' => 'wo/noc',
    //         'enduser_trade_license' => 'wo/enduser_trade_license',
    //         'enduser_passport' => 'wo/enduser_passport',
    //         'enduser_contract' => 'wo/enduser_contract',
    //         'vehicle_handover_person_id' => 'wo/vehicle_handover_person_id'
    //     ];
    //     // Loop through each file field
    //     foreach ($fileFields as $fileField => $path) {
    //         if ($request->hasFile($fileField)) {
    //             $file = $request->file($fileField);
    //             if ($file->isValid() && $file->getError() == UPLOAD_ERR_OK) {
    //                 $fileName = auth()->id() . '_' . time() . '.' . $file->extension();
    //                 $file->move(public_path($path), $fileName);

    //                 // Add the file name to the input array
    //                 $newData[$fileField] = $fileName;

    //                 // Collect file metadata for data history
    //                 $fileData[] = [
    //                     'file_name' => $fileName,
    //                     'file_type' => $file->getClientMimeType(),
    //                     // 'file_size' => $file->getSize(),
    //                     'file_path' => $path . '/' . $fileName
    //                 ];
    //             }
    //         }
    //     }

    //     $oldData = $workOrder->getOriginal();
    
    //     $changes = [];
    

    //     // Exclude specific fields and filter out non-NULL values and arrays
    //     $excludeFields = ['_token', 'customerCount', 'type', 'customer_type', 'comments','currency','wo_id'];
    //     $nonNullData = array_filter($request->all(), function ($value, $key) use ($excludeFields) {
    //         return !is_null($value) && !is_array($value) && !in_array($key, $excludeFields);
    //     }, ARRAY_FILTER_USE_BOTH);

    //     // Additional specific nested fields to store if not null
    //     $nestedFields = [
    //         'customer_company_number' => 'full',
    //         'customer_representative_contact' => 'full',
    //         'freight_agent_contact_number' => 'full',
    //         'transporting_driver_contact_number' => 'full'
    //     ];

    //     // Store each non-NULL, non-array field in the data history
    //     foreach ($nonNullData as $field => $value) { 
    //         WORecordHistory::create([
    //             'work_order_id' => $workOrder->id,
    //             'field_name' => $field,
    //             'old_value' => NULL,
    //             'new_value' => $value,
    //             'type' => 'SET',
    //             'user_id' => Auth::id(),
    //             'changed_at' => Carbon::now(),
    //         ]);
    //         if($field == 'so_amount') {
    //             WORecordHistory::create([
    //                 'work_order_id' => $workOrder->id,
    //                 'field_name' => 'currency',
    //                 'old_value' => NULL,
    //                 'new_value' => $request->currency,
    //                 'type' => 'SET',
    //                 'user_id' => Auth::id(),
    //                 'changed_at' => Carbon::now(),
    //             ]);
    //         }
    //     }
    //     // Store specific nested fields if not null
    //     foreach ($nestedFields as $field => $subField) {
    //         if (isset($request->$field[$subField]) && !is_null($request->$field[$subField])) {
    //             WORecordHistory::create([
    //                 'work_order_id' => $workOrder->id,
    //                 'field_name' => $field . '.' . $subField,
    //                 'old_value' => NULL,
    //                 'new_value' => $request->$field[$subField],
    //                 'type' => 'SET',
    //                 'user_id' => Auth::id(),
    //                 'changed_at' => Carbon::now(),
    //             ]);
    //         }
    //     }


    //     foreach ($newData as $field => $newValue) {
    //         if (is_array($newValue)) {
    //             $newValue = implode(',', $newValue);
    //         }
    
    //         $oldValue = $oldData[$field] ?? null;
    
    //         if (is_array($oldValue)) {
    //             $oldValue = implode(',', $oldValue);
    //         }
    
    //         if ($oldValue != $newValue) {
    //             $changes[] = [
    //                 'work_order_id' => $workOrder->id,
    //                 'user_id' => Auth::id(),
    //                 'field_name' => $field,
    //                 'old_value' => $oldValue,
    //                 'new_value' => $newValue,
    //                 'changed_at' => Carbon::now()
    //             ];
    //         }
    //     }
    
    //     if (!empty($changes)) { dd($changes);
    //         WORecordHistory::insert($changes);
    //     }
    
    //     $workOrder->update($newData);
    //     (new UserActivityController)->createActivity('Update '.$request->type.' work order');
    //         // Commit the transaction
    //         DB::commit();         
    //         return response()->json(['success' => true, 'message' => 'Work order updated successfully.']);
    //     } catch (\Exception $e) {
    //         // Rollback the transaction
    //         DB::rollBack();

    //         // Log the error for debugging
    //         Log::error('Error creating Work Order: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    //         // dd($nonNullData); dd($e);
    //         // return response()->json(['error' => 'Failed to create Work Order. Please try again.'], 500);
    //     }
    // }
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
    
            // Handle file uploads
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
            
            $fileData = [];
            foreach ($fileFields as $fileField => $path) {
                if ($request->hasFile($fileField)) {
                    $file = $request->file($fileField);
                    if ($file->isValid() && $file->getError() == UPLOAD_ERR_OK) {
                        $fileName = auth()->id() . '_' . time() . '.' . $file->extension();
                        $file->move(public_path($path), $fileName);
                        $newData[$fileField] = $fileName;
    
                        $fileData[] = [
                            'file_name' => $fileName,
                            'file_type' => $file->getClientMimeType(),
                            'file_path' => $path . '/' . $fileName
                        ];
                    }
                }
            }
    
            // Prepare old data for comparison
            $oldData = $workOrder->getOriginal();
            $changes = [];
    
            // Exclude specific fields and filter out non-NULL values and arrays
            $excludeFields = ['_token', 'customerCount', 'type', 'customer_type', 'comments', 'currency', 'wo_id'];
            $nonNullData = array_filter($request->all(), function ($value, $key) use ($excludeFields) {
                return !is_null($value) && !is_array($value) && !in_array($key, $excludeFields);
            }, ARRAY_FILTER_USE_BOTH);
    
            // // Record history for non-NULL, non-array fields
            // foreach ($nonNullData as $field => $value) {
            //     WORecordHistory::create([
            //         'work_order_id' => $workOrder->id,
            //         'field_name' => $field,
            //         'old_value' => null,
            //         'new_value' => $value,
            //         'type' => 'SET',
            //         'user_id' => $authId,
            //         'changed_at' => Carbon::now(),
            //     ]);
    
            //     if ($field == 'so_amount') {
            //         WORecordHistory::create([
            //             'work_order_id' => $workOrder->id,
            //             'field_name' => 'currency',
            //             'old_value' => null,
            //             'new_value' => $request->currency,
            //             'type' => 'SET',
            //             'user_id' => $authId,
            //             'changed_at' => Carbon::now(),
            //         ]);
            //     }
            // }
    
            // // Record history for specific nested fields
            // foreach ($nestedFields as $field) {
            //     if (isset($request->$field['full']) && !is_null($request->$field['full'])) {
            //         WORecordHistory::create([
            //             'work_order_id' => $workOrder->id,
            //             'field_name' => $field . '.full',
            //             'old_value' => null,
            //             'new_value' => $request->$field['full'],
            //             'type' => 'SET',
            //             'user_id' => $authId,
            //             'changed_at' => Carbon::now(),
            //         ]);
            //     }
            // }
    
            // // Log changes
            // foreach ($newData as $field => $newValue) {
            //     $oldValue = $oldData[$field] ?? null;
    
            //     if ($oldValue != $newValue) {
            //         $changes[] = [
            //             'work_order_id' => $workOrder->id,
            //             'user_id' => $authId,
            //             'field_name' => $field,
            //             'old_value' => $oldValue,
            //             'new_value' => $newValue,
            //             'type' => 'Change',
            //             'changed_at' => Carbon::now()
            //         ];
            //     }
            // }
    
            // if (!empty($changes)) {
            //     WORecordHistory::insert($changes);
            // }
    
            // Update the WorkOrder
            $workOrder->update($newData);
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
}
