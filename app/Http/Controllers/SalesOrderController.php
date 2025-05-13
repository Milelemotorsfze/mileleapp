<?php

namespace App\Http\Controllers;

use App\Models\So;
use App\Models\Quotation;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\UserActivities;
use App\Models\BookingRequest;
use App\Models\Closed;
use App\Models\ModelHasRoles;
use App\Models\Calls;
use App\Models\Vehicles;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\QuotationItem;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\User;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\Varaint;
use App\Models\PreOrder;
use App\Models\PreOrdersItems;
use App\Models\QuotationDetail;
use App\Models\Soitems;
use App\Models\Solog;
use App\Models\MasterModelLines;
use App\Models\SalesOrderHistory;
use App\Models\SalesOrderHistoryDetail;
use App\Models\SoVariant;

use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $useractivities = new UserActivities();
    $useractivities->activity = "Open Sales Order";
    $useractivities->users_id = Auth::id();
    $useractivities->save();

    if ($request->ajax()) {
        $status = $request->input('status');
        if ($status === "SalesOrder") {
            $id = Auth::user()->id;

            $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
            $query = So::select([
                'calls.name as customername',
                'calls.email',
                'calls.phone',
                'quotations.created_at',
                'quotations.deal_value',
                'quotations.sales_notes',
                'quotations.file_path',
                'users.name',
                'so.so_number',
                'so.id as soid',
                'so.so_date',
                'quotations.calls_id',
            ])
            ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
            ->leftJoin('users', 'so.sales_person_id', '=', 'users.id')
            ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
            ->groupBy('so.id');

            if (!$hasPermission) {
                $query->where('calls.sales_person', $id);
            }

            // Handle dynamic sorting
            $columns = [
                0 => 'so.so_number',
                1 => 'so.so_date',
                2 => 'users.name',
                3 => 'calls.name',
                4 => 'calls.phone',
                5 => 'calls.email',
                6 => 'quotations.created_at',
                7 => 'quotations.deal_value',
                8 => 'quotations.sales_notes'
            ];

            $orderColumnIndex = $request->input('order.0.column'); // Column index from request
            $orderDirection = $request->input('order.0.dir', 'asc'); // Sort direction (asc/desc)

            // Default to sorting by `so.so_date` if index is invalid
            $orderColumn = $columns[$orderColumnIndex] ?? 'so.so_date';

            // Apply the sorting dynamically
            $query->orderBy($orderColumn, $orderDirection);

            return DataTables::of($query)->toJson();
        }
    }

    return view('dailyleads.salesorder');
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

    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // return 1;
            $so = SO::findorFail($id);
            $quotation = Quotation::findOrFail($so->quotation_id);
            $call = Calls::findOrFail($quotation->calls_id);

            // $sodetails = So::where('quotation_id', $so->quotation_id)->first();
           
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') 
                 || Auth::user()->hasPermissionForSelectedRole('sales-view');
           
            $customerdetails = QuotationDetail::with('country', 'shippingPort', 'shippingPortOfLoad', 'paymentterms')->where('quotation_id', $quotation->id)->first();
            $soVariants = SoVariant::where('so_id', $id)->get();
            // if ($so->quotation_id) {
            //     $quotationItems = QuotationItem::where('quotation_id', $so->quotation_id)
            //         ->whereIn('reference_type', [
            //             'App\Models\Varaint',
            //             'App\Models\MasterModelLines',
            //             'App\Models\Brand'
            //         ])->get();
            //     foreach ($quotationItems as $item) {
            //         switch ($item->reference_type) {
            //             case 'App\Models\Varaint':
            //             $variantId = $item->reference_id;
            //             $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
            //             ->where(function ($query) use ($so) {
            //                 $query->whereNull('so_id')
            //                       ->orWhere('so_id', $so->id);
            //             })
            //             ->when(function ($query) use ($so) {
            //                 // Check if so_id exists with the same $sodetails->id
            //                 return DB::table('vehicles')->where('so_id', $so->id)->exists() === false;
            //             }, function ($query) {
            //                 // Apply gdn_id condition only if so_id is not the same
            //                 $query->whereNull('gdn_id');
            //             })
            //             ->when(!$hasPermission, function ($query) use($so){
            //                 $query->where(function ($subQuery) use($so) {
            //                     $subQuery->whereNull('booking_person_id')
            //                              ->orWhere('booking_person_id', $so->sales_person_id);
            //                 });
            //             })->get()->toArray();
                      
            //             $vehicles[$item->id] = $variantVehicles;
            //             break;
            //         case 'App\Models\MasterModelLines':
            //             $variants = Varaint::where('master_model_lines_id', $item->reference_id)->get();
            //             foreach ($variants as $variant) {
            //                 $variantId = $variant->id;
            //                 $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
            //                 ->where(function ($query) use ($so) {
            //                     $query->whereNull('so_id')
            //                           ->orWhere('so_id', $so->id);
            //                 })
            //                 ->when(function ($query) use ($so) {
            //                     // Check if so_id exists with the same $sodetails->id
            //                     return DB::table('vehicles')->where('so_id', $so->id)->exists() === false;
            //                 }, function ($query) {
            //                     // Apply gdn_id condition only if so_id is not the same
            //                     $query->whereNull('gdn_id');
            //                 })
            //                 ->when(!$hasPermission, function ($query) use($so) {
            //                     $query->where(function ($subQuery) use($so){
            //                         $subQuery->whereNull('booking_person_id')
            //                                  ->orWhere('booking_person_id', $so->sales_person_id);
            //                     });
            //                 })->get()->toArray();
            //                 $vehicles[$item->id] = $variantVehicles;
            //             }
            //             break;
            //         case 'App\Models\Brand':
            //             $variants = Varaint::where('brand_id', $item->reference_id)->get();
            //             foreach ($variants as $variant) {
            //                 $variantId = $variant->id;
            //                 $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
            //                 ->where(function ($query) use ($so) {
            //                     $query->whereNull('so_id')
            //                           ->orWhere('so_id', $so->id);
            //                 })
            //                 ->when(function ($query) use ($so) {
            //                     // Check if so_id exists with the same $sodetails->id
            //                     return DB::table('vehicles')->where('so_id', $so->id)->exists() === false;
            //                 }, function ($query) {
            //                     // Apply gdn_id condition only if so_id is not the same
            //                     $query->whereNull('gdn_id');
            //                 })
            //                 ->when(!$hasPermission, function ($query) use($so){
            //                     $query->where(function ($subQuery)use($so) {
            //                         $subQuery->whereNull('booking_person_id')
            //                                  ->orWhere('booking_person_id', $so->sales_person_id);
            //                     });
            //                 })->get()->toArray();
            //                 $vehicles[$item->id] = $variantVehicles;
            //             }
            //             break;
            //         default:
            //             break;
            //         }
            // }
            // } 
            $saleperson = User::find($quotation->created_by);
            $empProfile = EmployeeProfile::where('user_id', $quotation->created_by)->first(); 
            foreach($soVariants as $soVariant) {
                $selectedVehicleIds = $soVariant->so_items->pluck('vehicles_id')->toArray();
                $soVariant->soVehicles = Vehicles::where('varaints_id', $soVariant->variant_id)
                                            ->whereNotNull('vin')
                                            ->whereNull('gdn_id')
                                            ->where(function ($query) use ($so) {
                                                $query->whereNull('so_id')
                                                    ->orWhere('so_id', $so->id);
                                            })
                                            ->when(!$hasPermission, function ($query) use($so){
                                                $query->where(function ($subQuery)use($so) {
                                                    $subQuery->whereNull('booking_person_id')
                                                            ->orWhere('booking_person_id', $so->sales_person_id);
                                                });
                                            })
                                            ->select('id','gdn_id','vin')->get();
                                            
                $soVariant->selectedVehicleIds = $selectedVehicleIds;
               // check the quotation referenceid
               $soVariant->isgdnExist = 0;
               foreach($selectedVehicleIds as $eachVehicle) {
                    $eachVehicle = Vehicles::find($eachVehicle);
                    if($eachVehicle->gdn_id) {
                        $soVariant->isgdnExist = 1;
                        break; 
                    }
               }
            }
            $totalVehicles = $soVariants->sum('quantity');
            $variants = Varaint::select('id','name')->get();
            // return $soVariants;
            return view('salesorder.edit', compact('variants','totalVehicles','quotation', 'call', 'customerdetails','so', 
            'empProfile', 'saleperson','soVariants'));  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return $request->all();

        // DB::beginTransaction();
        // try {
                $so = SO::findorFail($id);
                $logEntries = [];
                $currentTimestamp = Carbon::now();

                // basic details Fields to check for changes
                $fields = [
                'so_number' => 'SO-' . $request->input('so_number'),
                'so_date' => $request->input('so_date'),
                'notes' => $request->input('notes') ?: null,
                'total' => $request->input('total_payment') ?: null,
                'receiving' => $request->input('receiving_payment') ?: null,
                'paidinso' => $request->input('payment_so') ?: null,
                'paidinperforma' => $request->input('advance_payment_performa') ?: null,
                'updated_by' => Auth::id(),
            ];

            foreach ($fields as $field => $newValue) {
                $oldValue = $so->$field;

                if ($newValue != $oldValue) {

                    $so->$field = $newValue;
                    $logEntries[] = [
                        'type' => is_null($oldValue) ? 'Set' : 'Change',
                        'model_type' => 'App\Models\SO',
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'created_at' => $currentTimestamp,
                        'updated_at' => $currentTimestamp
                    ];
                }
            }
            $so->save();
                  
            if (!empty($logEntries)) {
                $history = SalesOrderHistory::create([
                    'so_id' => $so->id,
                    'user_id' => Auth::id(),
                    'created_at' => $currentTimestamp
                ]);

                foreach ($logEntries as &$entry) {
                    $entry['sales_order_history_id'] = $history->id;
                }
                SalesOrderHistoryDetail::insert($logEntries);
            }

            

                // Soitems::where('so_id', $so->id)->update(['deleted_by' => Auth::id()]);
                // Soitems::where('so_id', $so->id)->delete();
                // Vehicles::where('so_id', $so->id)->update(['so_id' => null]);
                // get the variants count of existing so
                // $oldVariants = $so->vehicles()->groupBy('varaints_id')->pluck('varaints_id')->toArray();


                // $newVariants = $request->variants;
                // return $newVariants;
                // $latestSoHistory = SalesOrderHistory::where('so_id',$so->id)->latest()->first();
                // if(!$latestSoHistory) {
                //     $versionNumber = 1;
                // }else{
                //     $versionNumber = $latestSoHistory->version_number ?? 1 ;
                // }
                //     SalesOrderHistory::create([
                //             'so_id' => $so->id,
                //             'version_number' => $versionNumber,
                //             'user_id ' => Auth::id(),
                //             'created_at' => Carbon::now(),
                //         ]);
                // foreach ($newVariants as $key => $newVariant) {
                //     $newVariant = $oldVariants[$key]['variant_id'] ?? null;
                //     info($newVariant);
                   

                //     if (!in_array($newVariant, $oldVariant)) {
                //         // New Entry
                //          SalesOrderHistory::create([
                //             'so_id' => $soId,
                //             'change_type' => 'set',
                //             'variant_id' => $newVariant['variant_id'],
                //             'old_value' => null,
                //             'new_value' => json_encode($newVariant),
                //             'created_at' => now(),
                //         ]);

                //     }

                // }

                //  $soVariants = $request->variants;
                // if($soVariants) {
                //     foreach($soVariants as $key => $soVariant) {
                //         $quotationItem = QuotationItem::findOrFail($soVariant['quotation_item_id']);
                //         $soVariantdata  = New SoVariant();
                //         $soVariantdata->so_id = $so->id;
                //         $soVariantdata->variant_id = $soVariant['variant_id'];
                //         $soVariantdata->price = $quotationItem ? $quotationItem->unit_price : 0;
                //         $soVariantdata->description = $quotationItem ? $quotationItem->description : '';
                //         $soVariantdata->quantity = $quotationItem ? $quotationItem->quantity : 0;
                //         $soVariantdata->save();
                //         if(isset($soVariant['vehicles'])) {
                //             $vehicleIds = $soVariant['vehicles'];
                //             foreach($vehicleIds as $vehicleId) {
                //                 $soItem  = New Soitems();
                //                 $soItem->vehicles_id = $vehicleId;
                //                 $soItem->so_variant_id  = $soVariantdata->id;
                //                 $soItem->save();
                //                 $vehicle = Vehicles::find($vehicleId);
                //                 Vehicles::where('id', $vehicleId)->update(['so_id' => $so->id]);
                //             }
                //         }
                //     }
                // }


            //     DB::commit();
                return redirect()->back()->with('success', 'Sales Order updated successfully.');
            // } catch (\Exception $e) {
            //     DB::rollBack(); // Rollback transaction in case of error
            //     Log::error('Sales order updte faisls', ['error' => $e->getMessage()]);

            //     return redirect()->back()->withErrors('An error occurred while updating sales order.');
            // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        //
    }
    public function createsalesorder($callId) {
       
        $quotation = Quotation::where('calls_id', $callId)->first();
        $calls = Calls::find($callId);
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
        $customerdetails = QuotationDetail::with('country', 'shippingPort', 'shippingPortOfLoad', 'paymentterms')->where('quotation_id', $quotation->id)->first();
        $vehicles = [];
        if ($quotation) {
            $quotationItems = QuotationItem::where('quotation_id', $quotation->id)
                ->whereIn('reference_type', [
                    'App\Models\Varaint',
                    'App\Models\MasterModelLines',
                    'App\Models\Brand'
                ])->get();
            foreach ($quotationItems as $item) {
                switch ($item->reference_type) {
                    case 'App\Models\Varaint':
                    $variantId = $item->reference_id;
                    $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
                    ->whereNull('so_id')
                    ->whereNull('gdn_id')
                    ->when(!$hasPermission, function ($query) {
                        $query->where(function ($subQuery) {
                            $subQuery->whereNull('booking_person_id')
                                     ->orWhere('booking_person_id', Auth::id());
                        });
                    })->get()->toArray();
                    $vehicles[$item->id] = $variantVehicles;
                    break;
                case 'App\Models\MasterModelLines':
                   
                    $variants = Varaint::where('master_model_lines_id', $item->reference_id)->get();
                    foreach ($variants as $variant) {
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
                        ->whereNull('so_id')
                        ->whereNull('gdn_id')
                        ->when(!$hasPermission, function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->whereNull('booking_person_id')
                                         ->orWhere('booking_person_id', Auth::id());
                            });
                        })->get()->toArray();
                        $vehicles[$item->id] = $variantVehicles;
                    }
                    break;
                case 'App\Models\Brand':
                    $variants = Varaint::where('brand_id', $item->reference_id)->get();
                    foreach ($variants as $variant) {
                        $variantId = $variant->id;
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
                        ->whereNull('so_id')
                        ->whereNull('gdn_id')
                        ->when(!$hasPermission, function ($query) {
                            $query->where(function ($subQuery) {
                                $subQuery->whereNull('booking_person_id')
                                         ->orWhere('booking_person_id', Auth::id());
                            });
                        })->get()->toArray();
                        $vehicles[$item->id] = $variantVehicles;
                    }
                        break;
                    default:
                        break;
                    }
                    }
                    }
                     $totalVehicles = $quotationItems->sum('quantity');
                    $saleperson = User::find($quotation->created_by);
                    $empProfile = EmployeeProfile::where('user_id', $quotation->created_by)->first();
                    return view('salesorder.create', compact('vehicles', 'quotationItems', 'quotation', 'calls', 'customerdetails', 'empProfile', 'saleperson',
                'totalVehicles')); 
            }  
            public function storesalesorder(Request $request, $quotationId)
            {

                $request->validate([
                    'so_number' => 'required',
                ]);

            DB::beginTransaction();
                try {
        
                $qoutation = Quotation::find($quotationId);
                $so = New So();
                $so->quotation_id = $quotationId;
                $so->sales_person_id = $qoutation->created_by;
                $so_number = $request->input('so_number'); // Get the input value
                $so->so_number = 'SO-' . $so_number;    // Concatenate "SO-00" with the input value
                $so->so_date = $request->input('so_date');
                $so->notes = $request->input('notes');
                $so->total = $request->input('total_payment');
                $so->receiving = $request->input('receiving_payment');
                $so->paidinso = $request->input('payment_so');
                $so->paidinperforma = $request->input('advance_payment_performa');
                $so->created_by = auth()->id();
                $so->created_at = Carbon::now();
                $so->updated_at = Carbon::now();
                $so->save();
                $calls = Calls::find($qoutation->calls_id);
                $calls->status = "Closed";
                $calls->save();
                $closed = New Closed();
                $closed->date = $request->input('so_date');
                $closed->sales_notes = $request->input('notes');
                $closed->call_id = $calls->id;
                $closed->created_by = $qoutation->created_by;
                $closed->dealvalues = $request->input('total_payment');
                $closed->currency = $qoutation->currency;
                $closed->so_id = $so->id;
                $closed->save();
                // $vins = $request->input('vehicle_vin');
                // $selectedVinsWithNull = [];
                // $selectedVinsWithoutNull = [];
                // foreach ($vins as $quotationItemId => $selectedVins) {
                //     foreach ($selectedVins as $selectedVin) {
                //         if ($selectedVin === null) {
                //             $selectedVinsWithNull[$quotationItemId][] = $selectedVin;
                //         } else {
                //             $selectedVinsWithoutNull[$quotationItemId][] = $selectedVin;
                //         }
                //     }
                // }
                // $allVinsWithoutNull = [];
                // foreach ($selectedVinsWithoutNull as $selectedVins) {
                //     $allVinsWithoutNull = array_merge($allVinsWithoutNull, $selectedVins);
                // }
                // Vehicles::whereIn('vin', $allVinsWithoutNull)->update(['so_id' => $so->id]);
                // foreach ($selectedVinsWithoutNull as $quotationItemId => $selectedVins) {
                //     foreach ($selectedVins as $selectedVin) {
                //         $vehicle = Vehicles::where('vin', $selectedVin)->first();
                //         $soVinRelationship = new Soitems();
                //         $soVinRelationship->so_id = $so->id;
                //         $soVinRelationship->quotation_items_id = $quotationItemId;
                //         $soVinRelationship->vehicles_id = $vehicle->id;
                //         $soVinRelationship->save();
                //         $existingbookingpending = BookingRequest::where('quotation_items_id', $quotationItemId)->where('status', "New")->first();
                //         $existingbookingapproved = BookingRequest::where('quotation_items_id', $quotationItemId)->where('status', "Approved")->first();
                //         if($existingbookingpending)
                //         {
                //             $existingbookingpending->days = "10";
                //             $existingbookingpending->save();
                //         }
                //         else if ($existingbookingapproved)
                //         {
                //             $existingbookingapproved->days = "10";
                //             $existingbookingapproved->save();
                //             $updatebooking = Booking::where('booking_requests_id',$existingbookingapproved->id)->whereDate('booking_end_date', '>', now())->first();
                //             if($updatebooking)
                //             {
                //             $updatebooking->booking_end_date  = Carbon::now()->addDays(10);
                //             $updatebooking->save();
                //             $vehicle->reservation_end_date = Carbon::now()->addDays(10);
                //             $vehicle->save();
                //             }
                //             else
                //             {
                //                 $booking = New BookingRequest();
                //                 $booking->vehicle_id = $vehicle->id;
                //                 $booking->calls_id = $calls->id;
                //                 $booking->created_by = Auth::id();
                //                 $booking->status = "New";
                //                 $booking->days = "10";
                //                 $booking->save();   
                //             }
                //         }
                //         else
                //         {
                //         $booking = New BookingRequest();
                //         $booking->vehicle_id = $vehicle->id;
                //         $booking->calls_id = $calls->id;
                //         $booking->created_by = Auth::id();
                //         $booking->status = "New";
                //         $booking->days = "10";
                //         $booking->save();
                //         }
                //     }
                // }
          
                // new section 

                $soVariants = $request->variants;
                if($soVariants) {
                    foreach($soVariants as $key => $soVariant) {
                        $quotationItem = QuotationItem::findOrFail($soVariant['quotation_item_id']);
                        $soVariantdata  = New SoVariant();
                        $soVariantdata->so_id = $so->id;
                        $soVariantdata->variant_id = $soVariant['variant_id'];
                        $soVariantdata->price = $quotationItem ? $quotationItem->unit_price : 0;
                        $soVariantdata->description = $quotationItem ? $quotationItem->description : '';
                        $soVariantdata->quantity = $quotationItem ? $quotationItem->quantity : 0;
                        $soVariantdata->save();
                        if(isset($soVariant['vehicles'])) {
                            $vehicleIds = $soVariant['vehicles'];
                            foreach($vehicleIds as $vehicleId) {
                                $soItem  = New Soitems();
                                $soItem->vehicles_id = $vehicleId;
                                $soItem->so_variant_id  = $soVariantdata->id;
                                $soItem->save();
                                $vehicle = Vehicles::find($vehicleId);
                                Vehicles::where('id', $vehicleId)->update(['so_id' => $so->id]);

                                $existingbookingpending = BookingRequest::where('quotation_items_id', $quotationItem->id )->where('status', "New")->first();
                                $existingbookingapproved = BookingRequest::where('quotation_items_id', $quotationItem->id)->where('status', "Approved")->first();
                                if($existingbookingpending)
                                {
                                    $existingbookingpending->days = "10";
                                    $existingbookingpending->save();
                                }
                                else if ($existingbookingapproved)
                                {
                                    $existingbookingapproved->days = "10";
                                    $existingbookingapproved->save();
                                    $updatebooking = Booking::where('booking_requests_id',$existingbookingapproved->id)->whereDate('booking_end_date', '>', now())->first();
                                    if($updatebooking)
                                    {
                                        $updatebooking->booking_end_date  = Carbon::now()->addDays(10);
                                        $updatebooking->save();
                                        $vehicle->reservation_end_date = Carbon::now()->addDays(10);
                                        $vehicle->save();
                                    }
                                    else
                                    {
                                        $booking = New BookingRequest();
                                        $booking->vehicle_id = $vehicleId;
                                        $booking->calls_id = $calls->id;
                                        $booking->created_by = Auth::id();
                                        $booking->status = "New";
                                        $booking->days = "10";
                                        $booking->save();   
                                    }
                                }
                                else
                                {
                                    $booking = New BookingRequest();
                                    $booking->vehicle_id = $vehicleId;
                                    $booking->calls_id = $calls->id;
                                    $booking->created_by = Auth::id();
                                    $booking->status = "New";
                                    $booking->days = "10";
                                    $booking->save();
                                }
                            }

                        }
                    }

                    $solog = New Solog();
                    $solog->time = now()->format('H:i:s');
                    $solog->date = now()->format('Y-m-d');
                    $solog->status = 'SO Created';
                    $solog->created_by = Auth::id();
                    $solog->so_id = $so->id;
                    $solog->role = Auth::user()->selectedRole;
                    $solog->save();

                }else{
                      return redirect()->back()->with('error', 'Failed to create So, vehicle variant required to create so!'); 
                }
               
            DB::commit();
                return redirect()->route('dailyleads.index')->with('success', 'Sales Order created successfully.'); 
            } catch (\Exception $e) {
                DB::rollBack(); 
                Log::error('Sales order create fails', ['error' => $e->getMessage()]);

                return redirect()->back()->withErrors('An error occurred while creating sales order.Please contact Admin');
            }
               
    } 
        public function updatesalesorder ($id) 
        {
            $quotation = Quotation::where('calls_id', $id)->first();
            $calls = Calls::find($id);

            $sodetails = So::where('quotation_id', $quotation->id)->first();
           
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') 
                 || Auth::user()->hasPermissionForSelectedRole('sales-view');
            $soitems = Soitems::with('vehicle') // Ensure that vehicle is eager loaded
                      ->where('so_id', $sodetails->id)
                      ->get();
            $customerdetails = QuotationDetail::with('country', 'shippingPort', 'shippingPortOfLoad', 'paymentterms')->where('quotation_id', $quotation->id)->first();
            $vehicles = [];
            if ($quotation) {
                $quotationItems = QuotationItem::where('quotation_id', $quotation->id)
                    ->whereIn('reference_type', [
                        'App\Models\Varaint',
                        'App\Models\MasterModelLines',
                        'App\Models\Brand'
                    ])->get();
                foreach ($quotationItems as $item) {
                    switch ($item->reference_type) {
                        case 'App\Models\Varaint':
                        $variantId = $item->reference_id;
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
                        ->where(function ($query) use ($sodetails) {
                            $query->whereNull('so_id')
                                  ->orWhere('so_id', $sodetails->id);
                        })
                        ->when(function ($query) use ($sodetails) {
                            // Check if so_id exists with the same $sodetails->id
                            return DB::table('vehicles')->where('so_id', $sodetails->id)->exists() === false;
                        }, function ($query) {
                            // Apply gdn_id condition only if so_id is not the same
                            $query->whereNull('gdn_id');
                        })
                        ->when(!$hasPermission, function ($query) use($so){
                            $query->where(function ($subQuery) use($so) {
                                $subQuery->whereNull('booking_person_id')
                                         ->orWhere('booking_person_id', $sodetails->sales_person_id);
                            });
                        })->get()->toArray();
                      
                        $vehicles[$item->id] = $variantVehicles;
                        break;
                    case 'App\Models\MasterModelLines':
                        $variants = Varaint::where('master_model_lines_id', $item->reference_id)->get();
                        foreach ($variants as $variant) {
                            $variantId = $variant->id;
                            $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
                            ->where(function ($query) use ($sodetails) {
                                $query->whereNull('so_id')
                                      ->orWhere('so_id', $sodetails->id);
                            })
                            ->when(function ($query) use ($sodetails) {
                                // Check if so_id exists with the same $sodetails->id
                                return DB::table('vehicles')->where('so_id', $sodetails->id)->exists() === false;
                            }, function ($query) {
                                // Apply gdn_id condition only if so_id is not the same
                                $query->whereNull('gdn_id');
                            })
                            ->when(!$hasPermission, function ($query) use($sodetails) {
                                $query->where(function ($subQuery) use($sodetails){
                                    $subQuery->whereNull('booking_person_id')
                                             ->orWhere('booking_person_id', $sodetails->sales_person_id);
                                });
                            })->get()->toArray();
                            $vehicles[$item->id] = $variantVehicles;
                        }
                        break;
                    case 'App\Models\Brand':
                        $variants = Varaint::where('brand_id', $item->reference_id)->get();
                        foreach ($variants as $variant) {
                            $variantId = $variant->id;
                            $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->whereNotNull('vin')
                            ->where(function ($query) use ($sodetails) {
                                $query->whereNull('so_id')
                                      ->orWhere('so_id', $sodetails->id);
                            })
                            ->when(function ($query) use ($sodetails) {
                                // Check if so_id exists with the same $sodetails->id
                                return DB::table('vehicles')->where('so_id', $sodetails->id)->exists() === false;
                            }, function ($query) {
                                // Apply gdn_id condition only if so_id is not the same
                                $query->whereNull('gdn_id');
                            })
                            ->when(!$hasPermission, function ($query) use($sodetails){
                                $query->where(function ($subQuery)use($sodetails) {
                                    $subQuery->whereNull('booking_person_id')
                                             ->orWhere('booking_person_id', $sodetails->sales_person_id);
                                });
                            })->get()->toArray();
                            $vehicles[$item->id] = $variantVehicles;
                        }
                        break;
                    default:
                        break;
                    }
            }
            } 
            $saleperson = User::find($quotation->created_by);
            $empProfile = EmployeeProfile::where('user_id', $quotation->created_by)->first(); 
            foreach($quotationItems as $quotationItem) {
                $selectedVehicleIds = $quotationItem->soItems->pluck('vehicles_id')->toArray();
                $quotationItem->selectedVehicleIds = $selectedVehicleIds;
               // check the quotation referenceid
               $quotationItem->isgdnExist = 0;
               foreach($selectedVehicleIds as $eachVehicle) {
                    $eachVehicle = Vehicles::find($eachVehicle);
                    if($eachVehicle->gdn_id) {
                        $quotationItem->isgdnExist = 1;
                        break; 
                    }
               }
            }
            $totalVehicles = $quotationItems->sum('quantity');
            $variants = Varaint::select('id','name')->get();
            // return $quotationItems;
            return view('salesorder.update', compact('vehicles','variants','totalVehicles','quotationItems', 'quotation', 'calls', 'customerdetails','so', 'soitems', 'empProfile', 'saleperson'));  
        }
public function storesalesorderupdate(Request $request, $quotationId)
{
   
    DB::beginTransaction();
    try {
    // Validate and retrieve the Sales Order ID
    $so_id = $request->input('so_id');
    $so = So::findOrFail($so_id);
    // Update the Sales Order fields
    $so_number = $request->input('so_number'); // Get the input value
    $so->so_number = 'SO-' . $so_number; 
    $so->so_date = $request->input('so_date');
    $so->notes = $request->input('notes');
    $so->total = $request->input('total_payment');
    $so->receiving = $request->input('receiving_payment');
    $so->paidinso = $request->input('payment_so');
    $so->paidinperforma = $request->input('advance_payment_performa');
    $so->updated_by = auth()->id(); // or $request->user()->id
    $so->updated_at = Carbon::now();
    $so->save();

    // Delete existing Soitems records related to the Sales Order ID
    \Log::info('SO items deleted - Case 3-'.$so->id);
    Soitems::where('so_id', $so->id)->update(['deleted_by' => Auth::id()]);
    Soitems::where('so_id', $so->id)->delete();
    Vehicles::where('so_id', $so->id)->update(['so_id' => null]);
    \Log::info('Unassign SO id - Case 3-'.$so->id);
    // Process the selected VINs
    $vins = $request->input('vehicle_vin');
    $selectedVinsWithNull = [];
    $selectedVinsWithoutNull = [];

    // Separate VINs with null and without null
    foreach ($vins as $quotationItemId => $selectedVins) {
        foreach ($selectedVins as $selectedVin) {
            if (empty($selectedVin)) {
                $selectedVinsWithNull[$quotationItemId][] = $selectedVin;
            } else {
                $selectedVinsWithoutNull[$quotationItemId][] = $selectedVin;
            }
        }
    }

    // Update the vehicles with the Sales Order ID
    $allVinsWithoutNull = [];
    foreach ($selectedVinsWithoutNull as $selectedVins) {
        $allVinsWithoutNull = array_merge($allVinsWithoutNull, $selectedVins);
    }
    Vehicles::whereIn('id', $allVinsWithoutNull)->update(['so_id' => $so->id]);

    // Insert new Soitems records with the selected VINs
    foreach ($selectedVinsWithoutNull as $quotationItemId => $selectedVins) {
        foreach ($selectedVins as $selectedVin) {
            $vehicle = Vehicles::where('id', $selectedVin)->firstOrFail();
            $soVinRelationship = new Soitems([
                'so_id' => $so->id,
                'quotation_items_id' => $quotationItemId,
                'vehicles_id' => $vehicle->id
            ]);
            $soVinRelationship->save();

            // Handle BookingRequest updates
            $existingBookingPending = BookingRequest::where('quotation_items_id', $quotationItemId)
                ->where('status', "New")->first();
            $existingBookingApproved = BookingRequest::where('quotation_items_id', $quotationItemId)
                ->where('status', "Approved")->first();

            if ($existingBookingPending) {
                $existingBookingPending->days = "10";
                $existingBookingPending->save();
            } elseif ($existingBookingApproved) {
                $existingBookingApproved->days = "10";
                $existingBookingApproved->save();

                $updateBooking = Booking::where('booking_requests_id', $existingBookingApproved->id)
                    ->whereDate('booking_end_date', '>', now())->first();

                if ($updateBooking) {
                    $updateBooking->booking_end_date = Carbon::now()->addDays(10);
                    $updateBooking->save();
                    $vehicle->reservation_end_date = Carbon::now()->addDays(10);
                    $vehicle->save();
                } else {
                    $booking = new BookingRequest();
                    $booking->vehicle_id = $vehicle->id;
                    $booking->calls_id = $so->calls_id;
                    $booking->created_by = Auth::id();
                    $booking->status = "New";
                    $booking->days = "10";
                    $booking->save();
                }
            } else {
                $booking = new BookingRequest();
                $booking->vehicle_id = $vehicle->id;
                $booking->calls_id = $so->calls_id;
                $booking->created_by = Auth::id();
                $booking->status = "New";
                $booking->days = "10";
                $booking->save();
            }
        }
    }
    $solog = New Solog();
    $solog->time = now()->format('H:i:s');
    $solog->date = now()->format('Y-m-d');
    $solog->status = 'SO Updated';
    $solog->created_by = Auth::id();
    $solog->so_id = $so->id;
    $solog->role = Auth::user()->selectedRole;
    $solog->save();

    DB::commit();
    return redirect()->back()->with('success', 'Sales Order updated successfully.');
} catch (\Exception $e) {
    DB::rollBack(); // Rollback transaction in case of error
     Log::error('Sales order updte faisls', ['error' => $e->getMessage()]);

    return redirect()->back()->withErrors('An error occurred while updating sales order.');
}
  
}
public function cancel($id)
{
    // $quotation = Quotation::where('calls_id', $id)->first();
    // $calls = Calls::find($id);
    // $calls->status = 'Quoted';
    // $calls->save();
    // $leadclosed = Closed::where('call_id', $id)->first();
    // if($leadclosed)
    // {
    // $leadclosed->delete();
    // }
    // $so = So::where('quotation_id', $quotation->id)->first();

    DB::beginTransaction();

    try {

    $so = SO::find($id);
    if($so->quotation_id  && $so->quotation_id  != 0) {
        $quotation = Quotation::where('id', $so->quotation_id)->first();
        if($quotation){
            $calls = Calls::find($quotation->calls_id);
            $calls->status = 'Quoted';
            $calls->save();
            $leadclosed = Closed::where('call_id', $quotation->calls_id)->first();
            if($leadclosed)
            {
                $leadclosed->delete();
            }
        }
           
    }
    $soitems = Soitems::where('so_id', $so->id)->get();
    foreach ($soitems as $soitem) {
        $vehicle = Vehicles::find($soitem->vehicles_id);
        if ($vehicle) {
            $vehicle->so_id = null;
            $vehicle->reservation_start_date = null;
            $vehicle->reservation_end_date = null;
            $vehicle->booking_person_id = null;
            $vehicle->save();
            \Log::info('Unassign SO id - Case 4-'.$so->id);
        }
    }
    foreach ($soitems as $soitem) {
        $soitem->deleted_by = Auth::id();
        $soitem->save();
        \Log::info('SO items deleted - Case 4-'.$so->id);

        $soitem->delete();
    }

    $bookingrequest = BookingRequest::where('calls_id', $id)->first();
    if ($bookingrequest) {
        $bookingrequest->status = 'Rejected';
        $bookingrequest->save();
    }
    $solog = New Solog();
    $solog->time = now()->format('H:i:s');
    $solog->date = now()->format('Y-m-d');
    $solog->status = 'SO Cancel';
    $solog->created_by = Auth::id();
    $solog->so_id = $so->id;
    $solog->role = Auth::user()->selectedRole;
    $solog->save();
    $so->delete();

    DB::commit();
    return redirect()->back()->with('success', 'Sales Order and related items canceled successfully.');
} catch (\Exception $e) {
    DB::rollBack(); 

    Log::error('Sales Order Cancellation failed', ['error' => $e->getMessage()]);
    return redirect()->back()->with('error', 'Sales Order Cancellation failed.');
}
}
public function showSalesSummary($sales_person_id, $count_type)
{
    $salesperson = DB::table('users')->where('id', $sales_person_id)->first();
    switch ($count_type) {
        case 'Pending Leads':
        $leadsQuery = DB::table('calls')
        ->select([
            'calls.id',
            'calls.created_at',
            'calls.name',
            'calls.custom_brand_model',
            'calls.phone',
            'calls.email',
            'calls.remarks',
            'calls.type',
            'calls.location',
            'calls.language',
            'calls.priority',
            'master_model_lines.model_line',
            'users.name as created_by',
            'brands.brand_name'
        ])
        ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
        ->leftJoin('users', 'calls.created_by', '=', 'users.id')
        ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls.sales_person', $sales_person_id);
        $leadsQuery->where('calls.status', 'New');
        break;
        case 'Bulk Deals':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->where('calls.sales_person', $sales_person_id);
            $leadsQuery->whereIn('calls.leadtype', ['Bulk Deals', 'Special Orders']);
            break;
        case 'Prospecting':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'prospectings.medium',
                'prospectings.time',
                'prospectings.date',
                'prospectings.salesnotes',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id')
            ->where('calls.sales_person', $sales_person_id)
            ->where('calls.status', 'Prospecting')
            ->whereDate('calls.created_at', '>=', '2023-10-01')
            ->groupby('calls.id');
            break;
            case 'Follow Up':
                $leadsQuery = DB::table('calls')
                ->select([
                    'calls.id',
                    'calls.created_at',
                    'calls.name',
                    'calls.custom_brand_model',
                    'calls.phone',
                    'calls.email',
                    'calls.remarks',
                    'calls.type',
                    'calls.location',
                    'calls.language',
                    'calls.priority',
                    'master_model_lines.model_line',
                    'fellow_up.method',
                    'fellow_up.time',
                    'fellow_up.date',
                    'fellow_up.sales_notes',
                    'users.name as created_by',
                    'brands.brand_name'
                ])
                ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                ->leftJoin('users', 'calls.created_by', '=', 'users.id')
                ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->leftJoin('fellow_up', 'calls.id', '=', 'fellow_up.calls_id')
                ->where('calls.sales_person', $sales_person_id)
                ->where('calls.status', 'Follow Up')
                ->whereDate('calls.created_at', '>=', '2023-10-01')
                ->groupby('calls.id');
                break;
        case 'Quoted':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'quotations.deal_value',
                'quotations.date',
                'quotations.sales_notes',
                'quotations.file_path',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id')
            ->where('calls.sales_person', $sales_person_id)
            ->where('calls.status', 'Quoted')
            ->whereDate('calls.created_at', '>=', '2023-10-01')
            ->groupby('calls.id');
            break;
        case 'Rejected':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'lead_rejection.Reason',
                'lead_rejection.date',
                'lead_rejection.sales_notes',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->leftJoin('lead_rejection', 'calls.id', '=', 'lead_rejection.call_id')
            ->where('calls.sales_person', $sales_person_id)
            ->where('calls.status', 'Rejected')
            ->whereDate('calls.created_at', '>=', '2023-10-01')
            ->groupby('calls.id');
            break;
        case 'Sales Order':
            $leadsQuery = DB::table('so')
            ->select([
                'calls.name as customername',
                'calls.email',
                'calls.phone',
                'quotations.created_at',
                'quotations.deal_value',
                'quotations.sales_notes',
                'quotations.file_path',
                'users.name',
                'so.so_number',
                'so.so_date',
                'quotations.calls_id',
                'users.name as created_by',
            ])
            ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
            ->leftJoin('users', 'quotations.created_by', '=', 'users.id')
            ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->where('so.sales_person_id', $sales_person_id)
            ->groupby('so.id');
            break;
        default:
            // If count_type doesn't match any case, return an empty response
            return response()->json(['error' => 'Invalid count type'], 400);
    }
    // Return the JSON data for DataTables
    if (request()->ajax()) {
        return DataTables::of($leadsQuery)->make(true);
    }
    // Render the view
    return view('dailyleads.leadssummary', [
        'salesperson' => $salesperson,
        'countType' => $count_type,
    ]);
}
public function showSalespersonCommissions($sales_person_id, Request $request)
{
    $usdToAedRate = 3.67;
    $selectedMonth = $request->get('month') ?? now()->format('Y-m'); // Get the selected month, default to current
    // Fetch the salesperson's name
    $salesPerson = DB::table('users')->where('id', $sales_person_id)->select('name')->first();
    // Filter commissions for the selected salesperson and month
    $commissions = DB::table('vehicle_invoice')
        ->join('vehicle_invoice_items', 'vehicle_invoice.id', '=', 'vehicle_invoice_items.vehicle_invoice_id')
        ->join('so', 'vehicle_invoice.so_id', '=', 'so.id')
        ->join('users', 'so.sales_person_id', '=', 'users.id')
        ->leftJoin('vehicle_netsuite_cost', 'vehicle_invoice_items.vehicles_id', '=', 'vehicle_netsuite_cost.vehicles_id')
        ->where('so.sales_person_id', '=', $sales_person_id)
        ->where(DB::raw("DATE_FORMAT(vehicle_invoice.created_at, '%Y-%m')"), '=', $selectedMonth) // Filter by selected month
        ->select(
            'vehicle_invoice.invoice_number',
            'vehicle_invoice.id as invoice_id',
            'users.name',
            DB::raw('COUNT(vehicle_invoice_items.id) as total_invoice_items'),
            DB::raw('SUM(vehicle_netsuite_cost.cost) as total_vehicle_cost'),
            DB::raw("SUM(CASE WHEN vehicle_invoice.currency = 'USD' THEN vehicle_invoice_items.rate * $usdToAedRate ELSE vehicle_invoice_items.rate END) as total_rate_in_aed"),
            DB::raw('GROUP_CONCAT(vehicle_invoice_items.vehicles_id) as all_vehicles_ids'),
            'vehicle_invoice.currency'
        )
        ->groupBy('vehicle_invoice.id', 'users.name')
        ->get();
    // Calculate commission rates for the selected salesperson
    foreach ($commissions as $item) {
        $totalSales = $item->total_rate_in_aed;
        $commissionSlot = DB::table('commission_slots')
            ->where('min_sales', '<=', $totalSales)
            ->where(function($query) use ($totalSales) {
                $query->where('max_sales', '>=', $totalSales)
                      ->orWhereNull('max_sales');
            })
            ->orderBy('min_sales', 'desc')
            ->first();
        $item->commission_rate = $commissionSlot ? $commissionSlot->rate : 0;
    }
    // Return the view for this salesperson's commission
    return view('salesorder.commission', compact('commissions', 'selectedMonth', 'sales_person_id', 'salesPerson'));
}
    public function showVehicles($vehicle_invoice_id)
    {
    $usdToAedRate = 3.67;
    $invoiceData = DB::table('vehicle_invoice')
    ->join('so', 'vehicle_invoice.so_id', '=', 'so.id')
    ->join('clients', 'vehicle_invoice.clients_id', '=', 'clients.id')
    ->join('users', 'so.sales_person_id', '=', 'users.id')
    ->leftJoin('master_shipping_ports as pol_ports', 'vehicle_invoice.pol', '=', 'pol_ports.id') // Left join for POL
    ->leftJoin('master_shipping_ports as pod_ports', 'vehicle_invoice.pod', '=', 'pod_ports.id') // Left join for POD
    ->where('vehicle_invoice.id', $vehicle_invoice_id)
    ->select(
        'vehicle_invoice.invoice_number',
        'users.name as sales_person_name',
        'pol_ports.name as pol_name', // Port of Loading name, nullable
        'clients.name as customername',
        'clients.phone as customerphone',
        'clients.email as customeremail',
        'pod_ports.name as pod_name'  // Port of Discharge name, nullable
    )
    ->first();
    $vehicles = DB::table('vehicle_invoice_items')
        ->join('vehicle_invoice', 'vehicle_invoice.id', '=', 'vehicle_invoice_items.vehicle_invoice_id')
        ->join('vehicles', 'vehicle_invoice_items.vehicles_id', '=', 'vehicles.id')
        ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
        ->join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->leftJoin('vehicle_netsuite_cost', 'vehicles.id', '=', 'vehicle_netsuite_cost.vehicles_id')
        ->where('vehicle_invoice.id', '=', $vehicle_invoice_id)
        ->select(
            'vehicles.vin',
            'brands.brand_name',
            'master_model_lines.model_line',
            'varaints.name as variant_name',
            DB::raw('SUM(vehicle_netsuite_cost.cost) as total_vehicle_cost'),
            DB::raw("SUM(CASE WHEN vehicle_invoice.currency = 'USD' THEN vehicle_invoice_items.rate * $usdToAedRate ELSE vehicle_invoice_items.rate END) as total_rate_in_aed"),
            'vehicle_invoice.currency'
        )
        ->groupBy('vehicles.id', 'brands.brand_name', 'master_model_lines.model_line', 'varaints.name', 'vehicles.vin')
        ->get();
    foreach ($vehicles as $item) {
        $gross = $item->total_rate_in_aed - $item->total_vehicle_cost;
        if ($gross <= 0) {
            $item->commission_rate = 0;
            continue;
        }
        $totalSales = $item->total_rate_in_aed;
        $commissionSlot = DB::table('commission_slots')
            ->where('min_sales', '<=', $totalSales)
            ->where(function ($query) use ($totalSales) {
                $query->where('max_sales', '>=', $totalSales)
                    ->orWhereNull('max_sales');
            })
            ->orderBy('min_sales', 'desc')
            ->first();
        $item->commission_rate = $commissionSlot ? $commissionSlot->rate : 0;
    }
    return view('salesorder.vehicles', [
        'vehicles' => $vehicles,
        'salesPerson' => $invoiceData->sales_person_name,
        'pol' => $invoiceData->pol_name,
        'pod' => $invoiceData->pod_name,
        'customername' => $invoiceData->customername,
        'customerphone' => $invoiceData->customerphone,
        'customeremail' => $invoiceData->customeremail,
        'invoice_number' => $invoiceData->invoice_number
    ]);    
    }
    public function getSalespersons()
    {
        // Fetch salespersons based on role_id (Assuming 7 is the role_id for Sales Person)
        $salespersons = ModelHasRoles::where('role_id', 7)
            ->join('users', 'users.id', '=', 'model_has_roles.model_id')
            ->select('users.id', 'users.name')
            ->get();
    
        return response()->json(['salespersons' => $salespersons]);
    }

    public function updateSalesperson(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required|exists:so,id', // Correct table name
            'salesperson_id' => 'required|exists:users,id'
        ]);

        $salesOrder = So::find($request->sales_order_id);
        if ($salesOrder) {
            $salesOrder->sales_person_id = $request->salesperson_id;
            $salesOrder->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
    public function getVins(Request $request) {
    
      $data = [];
      $data['vehicles'] = Vehicles::where('varaints_id', $request->variant_id)
                    ->whereNull('gdn_id')->wherenotNull('vin')
                    ->where(function ($query) use ($request) {
                        $query->whereNull('so_id')
                              ->orWhere('so_id', $request->so_id);
                    })
                    ->select('id','vin')->get();

        $variant = Varaint::find($request->variant_id);
        $data['variant_description'] = ($variant->brand->brand_name ?? '') . ',' . ($variant->model_detail ?? '');

      return response()->json($data);
    }
    public function getVariants(Request $request) {
           
        $selectedVariantIds = $request->selectedVariantIds;
        $variants = Varaint::when(!empty($selectedVariantIds), function ($query) use ($selectedVariantIds) {
                $query->whereNotIn('id', $selectedVariantIds);
            })
            ->select('id', 'name')
            ->get();

        return response()->json($variants); 
    
    }
    public function checkUniqueSoNumber(Request $request) {
        
       $exists = SO::where('so_number', $request->so_number)
        ->when($request->filled('so_id'), function($query) use ($request) {
            return $query->where('id', '!=', $request->so_id);
        })
        ->whereDoesntHave('so_logs', function($query) {
            $query->where('status', 'SO Cancel');
        })
        ->exists();
        // passing $request->so_id available  and except the id as well in edit page
    return response()->json(['exists' => $exists]);
    }
}
