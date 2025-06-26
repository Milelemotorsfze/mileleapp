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
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
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
use App\Models\QuotationVins;
use App\Models\QuotationSubItem;
use App\Models\MuitlpleAgents;
use App\Models\QuotationFile;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Str;

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
                    'so.status',
                    'quotations.calls_id',
                ])
                    ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
                    ->leftJoin('users', 'so.sales_person_id', '=', 'users.id')
                    ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
                   ->where(function ($query) {
                        $query->where('so.status', '!=', 'Cancelled')
                            ->orWhereNull('so.status');
                    })
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
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show($id) {}

    public function viewQuotations($id)
    {
        try {
            $so = SO::findOrFail($id);
            $quotation = Quotation::findOrFail($so->quotation_id);
            $quotationVersionFiles = QuotationFile::where('quotation_id', $so->quotation_id)->get();
            $quotationDetail = QuotationDetail::with(['country', 'shippingPort', 'shippingPortOfLoad', 'paymentterms'])
                ->where('quotation_id', $quotation->id)
                ->first();
            
            if (!$quotationDetail) {
                return redirect()->back()->with('error', 'Quotation details not found.');
            }

            $empProfile = EmployeeProfile::where('user_id', $quotation->created_by)->first();
            $call = Calls::findOrFail($quotation->calls_id);

            return view('salesorder.quotation_versions', compact(
                'quotationVersionFiles',
                'quotation',
                'quotationDetail',
                'empProfile',
                'call',
                'so'
            ));
        } catch (\Exception $e) {
            \Log::error('Error in viewQuotations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the quotation versions.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Builder $builder, Request $request, $id)
    {
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

        foreach ($soVariants as $soVariant) {
            $selectedVehicleIds = $soVariant->so_items->pluck('vehicles_id')->toArray();
            $selectedVehicles = Vehicles::whereIn('id', $selectedVehicleIds)
                ->whereNotNull('vin')
                ->select('id', 'vin', 'gdn_id')
                ->get();
            $availableVehicles = Vehicles::where('varaints_id', $soVariant->variant_id)
                ->whereNotNull('vin')
                ->whereNull('gdn_id')
                ->where(function ($query) use ($so) {
                    $query->whereNull('so_id')
                        ->orWhere('so_id', $so->id);
                })
                ->when(!$hasPermission, function ($query) use ($so) {
                    $query->where(function ($subQuery) use ($so) {
                        $subQuery->whereNull('booking_person_id')
                            ->orWhere('booking_person_id', $so->sales_person_id);
                    });
                })
                ->whereNotIn('id', $selectedVehicleIds)
                ->select('id', 'gdn_id', 'vin')->get();

            $soVariant->soVehicles = $selectedVehicles->merge($availableVehicles);
            $soVariant->selectedVehicleIds = $selectedVehicleIds;
            $soVariant->isgdnExist = 0;
            //    return $selectedVehicleIds;
            foreach ($selectedVehicleIds as $eachVehicle) {
                $eachVehicle = Vehicles::find($eachVehicle);
                if ($eachVehicle && $eachVehicle->gdn_id) {
                    $soVariant->isgdnExist = 1;
                    break;
                }
            }
        }

        $totalVehicles = $soVariants->sum('quantity');
        $variants = Varaint::select('id', 'name')->get();
        $salesOrderHistories = SalesOrderHistoryDetail::whereHas('salesOrderHistory', function ($query) use ($id) {
            $query->where('so_id', $id);
        })
            ->orderBy('id', 'DESC')
            ->with([
                'salesOrderHistory' => function ($query) use ($id) {
                    $query->select('*');
                },
                'SoVariant'  => function ($query) {
                    $query->select('id', 'variant_id', 'so_id');
                },
                'SoVariant.variant'  => function ($query) {
                    $query->select('id', 'name');
                },
                'salesOrderHistory.user'  => function ($query) {
                    $query->select('id', 'name');
                },
            ]);

        if (request()->ajax()) {
            return DataTables::of($salesOrderHistories)
                ->addIndexColumn()
                ->editColumn('created_at', function ($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->addColumn('created_by', function ($query) {
                    return $query->salesOrderHistory->user->name ?? '';
                })
                ->addColumn('version', function ($query) use ($id) {
                    $historyIds = SalesOrderHistory::where('so_id', $id)
                        ->orderBy('id', 'ASC')
                        ->pluck('id')
                        ->toArray();

                    $versionNumber = array_search($query->sales_order_history_id, $historyIds) + 1;
                    return 'Version ' . $versionNumber;
                })
                ->editColumn('field_name', function ($query) {
                    switch ($query->field_name) {
                        case 'so_variant_id':
                            return 'Variant';
                        case 'vehicles_id':
                            return 'Vin';
                        default:
                            return $query->field_name ?? '';
                    }
                })
                ->addColumn('so_variant_id', function ($query) {
                    if (
                        $query->field_name == 'price' || $query->field_name == 'description' || $query->field_name == 'quantity'
                        || $query->field_name == 'vehicles_id'
                    ) {
                        return  optional($query->SoVariant()->withTrashed()->first()->variant)->name ?? '';
                    }
                })
                ->editColumn('old_value', function ($query) {
                    if (!is_null($query->old_value)) {
                        if ($query->field_name == 'so_variant_id') {
                            return optional($query->SoVariant()->withTrashed()->first()->variant)->name ?? '';
                        } else if ($query->field_name == 'vehicles_id') {
                            $soItem = $query->so_item;
                            if ($soItem) {
                                return optional($soItem->vehicle()->withTrashed()->first())->vin ?? '';
                            }
                            return '';
                        } else {
                            return $query->old_value ?? '';
                        }
                    }
                    return "";
                })
                ->editColumn('new_value', function ($query) {
                    if (!is_null($query->new_value)) {
                        if ($query->field_name == 'so_variant_id') {
                            return optional($query->SoVariant()->withTrashed()->first()->variant)->name ?? '';
                        } else if ($query->field_name == 'vehicles_id') {
                            $soItem = $query->so_item;
                            if ($soItem) {
                                return optional($soItem->vehicle()->withTrashed()->first())->vin ?? '';
                            }
                            return '';
                        } else {
                            return $query->new_value ?? '';
                        }
                    }
                    return "";
                })
                ->rawColumns(['version', 'so_variant_id', 'created_by'])
                ->toJson();
        }

        return view('salesorder.edit', compact(
            'variants',
            'totalVehicles',
            'quotation',
            'call',
            'customerdetails',
            'so',
            'empProfile',
            'saleperson',
            'soVariants'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $so = SO::find($id);
        if (!$so) {
            return redirect()->back()->withErrors('Sales Order not found.');
        }
        DB::beginTransaction();
        try {
            $logEntries = [];
            $currentTimestamp = Carbon::now();
            $priceChanged = false;

            // VIN uniqueness validation across all variants
            $soVariantsInput = $request->input('variants', []);
            $allVins = [];
            foreach ($soVariantsInput as $variant) {
                if (isset($variant['vehicles']) && is_array($variant['vehicles'])) {
                    foreach ($variant['vehicles'] as $vinId) {
                        if (in_array($vinId, $allVins)) {
                            DB::rollBack();
                            return redirect()->back()->withErrors('A VIN cannot be assigned to more than one variant. Please ensure all VINs are unique across variants.');
                        }
                        $allVins[] = $vinId;
                    }
                }
            }

            // basic details Fields to check for changes
            $fields = [
                'so_number' => 'SO-' . $request->input('so_number'),
                'so_date' => $request->input('so_date'),
                'notes' => $request->input('notes') ?: null,
                'total' => $request->input('total_payment') ?: null,
                'receiving' => $request->input('receiving_payment') ?: null,
                'paidinso' => $request->input('payment_so') ?: null,
                'paidinperforma' => $request->input('advance_payment_performa') ?: null,
            ];

            foreach ($fields as $field => $newValue) {
                $oldValue = $so->$field;
                if ($newValue != $oldValue) {
                    $so->$field = $newValue;
                    $logEntries[] = [
                        'type' => is_null($oldValue) ? 'Set' : 'Change',
                        'so_item_id' => null,
                        'so_variant_id' => null,
                        'field_name' => $field,
                        'old_value' =>  is_null($oldValue) ? null : $oldValue,
                        'new_value' => $newValue
                    ];
                }
            }
            $so->save();

            // Handle deleted variants
            if ($request->has('deleted_so_variant_ids')) {
                $deletedVariantIds = $request->input('deleted_so_variant_ids');
                foreach ($deletedVariantIds as $variantId) {
                    $variant = SoVariant::find($variantId);
                    if (!$variant) continue; // Null check added
                    $logEntries[] = [
                        'type' => 'Delete',
                        'so_item_id' => null,
                        'so_variant_id' => $variantId,
                        'field_name' => 'variant',
                        'old_value' => $variant->variant->name ?? '',
                        'new_value' => null
                    ];
                    // Remove the SO items and update the vehicle's SO ID
                    $logEntries = array_merge($logEntries, $this->removeSoItems($variantId, $variant->soVehicles ? $variant->soVehicles->pluck('vehicles_id')->toArray() : []));
                    $variant->delete();
                }
            }

            // Process variants
            $soVariants = $request->input('variants');
            if ($soVariants) {
                foreach ($soVariants as $key => $soVariant) {
                    // Check variant existing or not
                    $existingVariant = null;
                    if (isset($soVariant['so_variant_id'])) {
                        $existingVariant = SoVariant::find($soVariant['so_variant_id']);
                    }

                    if (!$existingVariant) {
                        // New variant
                        $soVariantdata = new SoVariant();
                        $soVariantdata->so_id = $so->id;
                        $soVariantdata->variant_id = $soVariant['variant_id'];
                        $soVariantdata->price = $soVariant['price'];
                        $soVariantdata->description = $soVariant['description'];
                        $soVariantdata->quantity = $soVariant['quantity'];
                        $soVariantdata->save();

                        // New variant means price has changed
                        $priceChanged = true;

                        foreach (['so_variant_id', 'description', 'price', 'quantity'] as $field) {
                            $logEntries[] = [
                                'type' => 'Set',
                                'so_item_id' => null,
                                'so_variant_id' => $soVariantdata->id,
                                'field_name' => $field,
                                'old_value' => null,
                                'new_value' => $field == 'so_variant_id' ? $soVariantdata->id : $soVariantdata->$field
                            ];
                        }

                        if (isset($soVariant['vehicles'])) {
                            $newVehicles = $soVariant['vehicles'];
                            foreach ($newVehicles as $vehicleId) {
                                $soItem = Soitems::create(['vehicles_id' => $vehicleId, 'so_variant_id' => $soVariantdata->id]);
                                if (!$soItem) continue; // Null check added
                                $logEntries[] = [
                                    'type' => 'Set',
                                    'so_item_id' => $soItem->id,
                                    'so_variant_id' => $soVariantdata->id,
                                    'field_name' => 'vehicles_id',
                                    'old_value' => null,
                                    'new_value' => $vehicleId
                                ];
                                Vehicles::where('id', $vehicleId)->update(['so_id' => $so->id]);
                            }
                        }
                    } else if ($existingVariant) {
                        // Existing variant - check for changes
                        $fields = [
                            'variant_id' => $soVariant['variant_id'],
                            'description' => $soVariant['description'],
                            'price' => $soVariant['price'],
                            'quantity' => $soVariant['quantity']
                        ];

                        foreach ($fields as $field => $newValue) {
                            $oldValue = $existingVariant->$field;
                            if ($newValue != $oldValue) {
                                // If price has changed, set the flag
                                if ($field === 'price' && $newValue != $oldValue) {
                                    $priceChanged = true;
                                }
                                $existingVariant->$field = $newValue;
                                $logEntries[] = [
                                    'type' => 'Change',
                                    'so_item_id' => null,
                                    'so_variant_id' => $existingVariant->id,
                                    'field_name' => $field,
                                    'old_value' => $oldValue,
                                    'new_value' => $newValue
                                ];
                            }
                        }
                        $existingVariant->save();

                        // Handle vehicles
                        if (isset($soVariant['vehicles'])) {
                            $currentVehicles = $existingVariant && $existingVariant->so_items ? $existingVariant->so_items->pluck('vehicles_id')->toArray() : [];
                            $newVehicles = $soVariant['vehicles'];
                            // If no vehicles are selected, remove all existing ones
                            if (empty($newVehicles)) {
                                $logEntries = array_merge($logEntries, $this->removeSoItems($existingVariant->id, $currentVehicles));
                            } else {
                                $addedVehicles = array_diff($newVehicles, $currentVehicles);
                                $removedVehicles = array_diff($currentVehicles, $newVehicles);
                                // Add new vehicles
                                foreach ($addedVehicles as $vehicleId) {
                                    $soItem = Soitems::create(['vehicles_id' => $vehicleId, 'so_variant_id' => $existingVariant->id]);
                                    if (!$soItem) continue; // Null check added
                                    $logEntries[] = [
                                        'type' => 'Set',
                                        'so_item_id' => $soItem->id,
                                        'so_variant_id' => $existingVariant->id,
                                        'field_name' => 'vehicles_id',
                                        'old_value' => null,
                                        'new_value' => $vehicleId
                                    ];
                                    Vehicles::where('id', $vehicleId)->update(['so_id' => $so->id]);
                                }
                                // Remove unselected vehicles
                                if (!empty($removedVehicles)) {
                                    $logEntries = array_merge($logEntries, $this->removeSoItems($existingVariant->id, $removedVehicles));
                                }
                            }
                        } else {
                            // If vehicles array is not set, remove all existing vehicles
                            $currentVehicles = $existingVariant && $existingVariant->so_items ? $existingVariant->so_items->pluck('vehicles_id')->toArray() : [];
                            if (!empty($currentVehicles)) {
                                $logEntries = array_merge($logEntries, $this->removeSoItems($existingVariant->id, $currentVehicles));
                            }
                        }
                    } // end else if $existingVariant
                }
            }

            if (!empty($logEntries)) {
                $history = SalesOrderHistory::create([
                    'so_id' => $so->id,
                    'user_id' => Auth::id(),
                    'created_at' => $currentTimestamp
                ]);

                foreach ($logEntries as &$entry) {
                    $entry['sales_order_history_id'] = $history->id;
                    $entry['created_at'] = $currentTimestamp;
                    $entry['updated_at'] = $currentTimestamp;
                }
                SalesOrderHistoryDetail::insert($logEntries);
            }

            // Set status to Pending if price has changed
            if ($priceChanged) {
                $so->status = 'Pending';
                $so->save();
            }

            // Generate latest quotation
            $file = $this->generateLatestQuotation($so->id);

            DB::commit();
            return redirect()->back()->with('success', 'Sales Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sales order update fails', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('An error occurred while updating sales order.');
        }
    }

    public function removeSoItems($soVariantId, $vehicleIds)
    {
        $logEntries = [];

        // Fetch SO items to be deleted
        $soItems = Soitems::whereIn('vehicles_id', $vehicleIds)
            ->where('so_variant_id', $soVariantId)
            ->get();

        if ($soItems->isEmpty()) {
            return $logEntries;
        }

        foreach ($soItems as $soItem) {
            // Log the removal of each SO item
            $logEntries[] = [
                'type' => 'Unset',
                'so_item_id' => $soItem->id,
                'so_variant_id' => $soVariantId,
                'field_name' => 'vehicles_id',
                'old_value' => $soItem->vehicles_id,
                'new_value' => null
            ];

            // Remove the SO item and update the vehicle's SO ID
            $soItem->delete();
            Vehicles::where('id', $soItem->vehicles_id)->update(['so_id' => null]);
        }

        return $logEntries;
    }

    public function generateLatestQuotation($id)
    {

        $so = SO::findOrFail($id);
        $quotation = Quotation::findOrFail($so->quotation_id);
        $call = Calls::findOrFail($quotation->calls_id);

        $existingQuotationItemIds = QuotationItem::where('reference_type', 'App\Models\Varaint')->where('quotation_id', $so->quotation_id)->pluck('id')->toArray();
        $latestQuotationItemsExistingIds = SoVariant::where('so_id', $id)->pluck('quotation_item_id')->toArray();

        $removedQuotationItemIds = array_diff($existingQuotationItemIds, $latestQuotationItemsExistingIds);

        if (!empty($removedQuotationItemIds)) {
            // Delete related data from related tables
            // MultipleAgentSystemCode::whereIn('quotation_items_id', $removedQuotationItemIds)->delete();
            QuotationVins::whereIn('quotation_items_id', $removedQuotationItemIds)->delete();
            BookingRequest::whereIn('quotation_items_id', $removedQuotationItemIds)->delete();
            $quotationSubItems = QuotationSubItem::whereIn('quotation_item_parent_id', $removedQuotationItemIds)->pluck('quotation_item_id')->toArray();
            // delete quotation item sub items from quotation items table (addon,spareparts..)
            QuotationItem::where('quotation_id', $so->quotation_id)->whereIn('id', $quotationSubItems)->delete();
            // delete from subitems table
            QuotationSubItem::whereIn('quotation_item_parent_id', $removedQuotationItemIds)->delete();
            // Delete the quotation items
            QuotationItem::whereIn('id', $removedQuotationItemIds)->delete();
        }
        // get all so varianta and create new entry in Quotation items if quotation id is null => new entry
        $soVariants = SoVariant::where('so_id', $id)->get();

        foreach ($soVariants as $soVariant) {
            if (is_null($soVariant->quotation_item_id)) {
                // Create a new entry in quotation_items
                $quotationItem = new QuotationItem();
                $quotationItem->quotation_id = $so->quotation_id;
                $quotationItem->reference_type = 'App\Models\Varaint';
                $quotationItem->reference_id  = $soVariant->variant_id;
                $quotationItem->model_line_id  = $soVariant->variant->master_model_lines->id ?? NULL;
                $quotationItem->brand_id  =    $soVariant->variant->brand->id ?? NULL;
                $quotationItem->description =  $soVariant->description;
                $quotationItem->unit_price =  $soVariant->price;
                $quotationItem->quantity =    $soVariant->quantity;
                $quotationItem->is_addon =   0;
                $quotationItem->is_enable =  1;
                $quotationItem->total_amount =  $soVariant->quantity *  $soVariant->price;
                $quotationItem->save();

                // Update the quotation_item_id in the so variant
                $soVariant->quotation_item_id = $quotationItem->id;
                $soVariant->save();
            } else {
                // Update the existing quotation item
                QuotationItem::where('id', $soVariant->quotation_item_id)->update([
                    'reference_id' => $soVariant->variant_id,
                    'model_line_id' => $soVariant->variant->master_model_lines->id ?? NULL,
                    'brand_id'  => $soVariant->variant->brand->id ?? NULL,
                    'description' => $soVariant->description,
                    'unit_price' => $soVariant->price,
                    'quantity' => $soVariant->quantity,
                    'is_addon' => 0,
                    'is_enable' => 1,
                    'total_amount' => $soVariant->quantity * $soVariant->price,
                ]);
            }
        }

        $quotationDetail = QuotationDetail::with('country')->where('quotation_id', $quotation->id)->first();
        $vehicles =  QuotationItem::where("reference_type", 'App\Models\Varaint')
            ->where('quotation_id', $quotation->id)->get();
        $otherVehicles = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', true)
            ->where('is_addon', false)
            ->get();
        $vehicleWithBrands = QuotationItem::where('quotation_id', $quotation->id)
            ->whereIn("reference_type", ['App\Models\Brand', 'App\Models\MasterModelLines'])
            ->where('is_addon', false)
            ->get();
        $alreadyAddedQuotationIds = QuotationSubItem::where('quotation_id', $quotation->id)
            ->pluck('quotation_item_id')->toArray();
        $directlyAddedAddons =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();
        $hidedDirectlyAddedAddonSum =  QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', false)
            ->where('is_addon', true)
            ->sum('total_amount');
        $addons = QuotationItem::whereIn('reference_type', ['App\Models\AddonDetails', 'App\Models\Addon'])
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $OtherAddons = QuotationItem::whereNull('reference_type')
            ->whereNull('reference_id')
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', true)
            ->where('is_addon', true)->get();
        $hidedAddonSum = QuotationItem::where('reference_type', 'App\Models\AddonDetails')
            ->whereNotIn('id', $alreadyAddedQuotationIds)
            ->where('quotation_id', $quotation->id)
            ->where('is_enable', false)->sum('total_amount');
        $addonsTotalAmount = $hidedDirectlyAddedAddonSum + $hidedAddonSum;
        $shippingCharges = QuotationItem::where('reference_type', 'App\Models\Shipping')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingDocuments = QuotationItem::where('reference_type', 'App\Models\ShippingDocuments')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $otherDocuments = QuotationItem::where('reference_type', 'App\Models\OtherLogisticsCharges')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $shippingCertifications = QuotationItem::where('reference_type', 'App\Models\ShippingCertification')
            ->where('is_enable', true)
            ->where('quotation_id', $quotation->id)->get();
        $salesPersonDetail = EmployeeProfile::where('user_id', $quotation->created_by)->first();
        $salesperson = User::find($quotation->created_by);
        $data = [];
        $data['sales_person'] = $salesperson->name ?? '';
        $data['sales_office'] = 'Central 191';
        $data['sales_phone'] = '';
        $data['sales_email'] = $salesperson->email ?? '';
        $data['client_id'] = $call->id;
        $data['client_email'] = $call->email ?? '';
        $data['client_name'] = $call->name ?? '';
        $data['client_contact_person'] = $call->client_contact_person ?? '';
        $data['client_phone'] = $call->phone ?? '';
        $data['client_address'] = $call->address ?? '';
        $data['document_number'] = $quotation->id;
        $data['company'] = $call->company_name ?? '';
        $data['document_date'] = Carbon::parse($quotation->date)->format('M d,Y');
        if ($salesPersonDetail) {
            $data['sales_office'] = $salesPersonDetail->location->name ?? '';
            $data['sales_phone'] = $salesPersonDetail->contact_number ?? '';
        }

        $shippingHidedItemAmount = QuotationItem::where('is_enable', false)
            ->where('quotation_id', $quotation->id)
            ->whereIn('reference_type', [
                'App\Models\ShippingDocuments',
                'App\Models\Shipping',
                'App\Models\ShippingCertification',
                'App\Models\OtherLogisticsCharges'
            ])
            ->sum('total_amount');

        $vehicleCount = $vehicles->count() + $otherVehicles->count() + $vehicleWithBrands->count();

        if ($vehicleCount > 0) {
            $shippingChargeDistriAmount = $shippingHidedItemAmount / $vehicleCount;
        } else {
            $shippingChargeDistriAmount = 0;
        }

        $aed_to_eru_rate = Setting::where('key', 'aed_to_euro_convertion_rate')->first();
        $aed_to_usd_rate = Setting::where('key', 'aed_to_usd_convertion_rate')->first();
        $multiplecp = MuitlpleAgents::where('quotations_id', $quotation->id)->where('agents_id', '!=', $quotationDetail->agents_id)->get();

        $pdfFile = Pdf::loadView('proforma.proforma_invoice', compact(
            'so',
            'multiplecp',
            'quotation',
            'data',
            'quotationDetail',
            'aed_to_usd_rate',
            'aed_to_eru_rate',
            'vehicles',
            'addons',
            'shippingCharges',
            'shippingDocuments',
            'otherDocuments',
            'shippingCertifications',
            'directlyAddedAddons',
            'addonsTotalAmount',
            'otherVehicles',
            'vehicleWithBrands',
            'OtherAddons',
            'shippingChargeDistriAmount'
        ));

        $filename = 'quotation_' . $quotation->id . '.pdf';
        $generatedPdfDirectory = public_path('Quotations');
        $directory = public_path('storage/quotation_files');
        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        $pdfFile->save($generatedPdfDirectory . '/' . $filename);
        $quotationController = new QuotationController();
        $pdf = $quotationController->pdfMerge($quotation->id);
        $file = 'Quotation_' . $quotation->id . '_' . date('Y_m_d_H_i_s') . '.pdf';
        $pdf->Output($directory . '/' . $file, 'F');
        $quotationFile = new QuotationFile();
        $quotationFile->quotation_id = $quotation->id;
        $quotationFile->file_name = $file;
        $quotationFile->save();

        return $file;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        //
    }

    public function createsalesorder($callId)
    {

        $quotation = Quotation::where('calls_id', $callId)->first();
        $calls = Calls::findOrFail($callId);
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
        // return $saleperson;
        $empProfile = EmployeeProfile::where('user_id', $quotation->created_by)->first();
        return view('salesorder.create', compact(
            'vehicles',
            'quotationItems',
            'quotation',
            'calls',
            'customerdetails',
            'empProfile',
            'saleperson',
            'totalVehicles'
        ));
    }

    public function storesalesorder(Request $request, $quotationId)
    {

        $request->validate([
            'so_number' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $quotation = Quotation::find($quotationId);
            $so = new So();
            $so->quotation_id = $quotationId;
            $so->sales_person_id = $quotation->created_by;
            $so_number = $request->input('so_number'); // Get the input value
            $so->so_number = 'SO-' . $so_number;    // Concatenate "SO-00" with the input value
            $so->so_date = $request->input('so_date');
            $so->notes = $request->input('notes');
            $so->total = $request->input('total_payment');
            $so->receiving = $request->input('receiving_payment');
            $so->paidinso = $request->input('payment_so');
            $so->paidinperforma = $request->input('advance_payment_performa');
            $so->status = 'Approved';
            $so->created_by = auth()->id();
            $so->created_at = Carbon::now();
            $so->updated_at = Carbon::now();
            $so->save();
            $calls = Calls::find($quotation->calls_id);
            $calls->status = "Closed";
            $calls->save();
            $closed = new Closed();
            $closed->date = $request->input('so_date');
            $closed->sales_notes = $request->input('notes');
            $closed->call_id = $calls->id;
            $closed->created_by = $quotation->created_by;
            $closed->dealvalues = $request->input('total_payment');
            $closed->currency = $quotation->currency;
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
            if ($soVariants) {
                foreach ($soVariants as $key => $soVariant) {
                    $quotationItem = QuotationItem::findOrFail($soVariant['quotation_item_id']);
                    $soVariantdata  = new SoVariant();
                    $soVariantdata->so_id = $so->id;
                    $soVariantdata->variant_id = $soVariant['variant_id'];
                    $soVariantdata->quotation_item_id = $soVariant['quotation_item_id'];
                    $soVariantdata->price = $quotationItem ? $quotationItem->unit_price : 0;
                    $soVariantdata->description = $quotationItem ? $quotationItem->description : '';
                    $soVariantdata->quantity = $quotationItem ? $quotationItem->quantity : 0;
                    $soVariantdata->save();
                    if (isset($soVariant['vehicles'])) {
                        $vehicleIds = $soVariant['vehicles'];
                        foreach ($vehicleIds as $vehicleId) {
                            $soItem  = new Soitems();
                            $soItem->vehicles_id = $vehicleId;
                            $soItem->so_variant_id  = $soVariantdata->id;
                            $soItem->save();
                            $vehicle = Vehicles::find($vehicleId);
                            Vehicles::where('id', $vehicleId)->update(['so_id' => $so->id]);

                            $existingbookingpending = BookingRequest::where('quotation_items_id', $quotationItem->id)->where('status', "New")->first();
                            $existingbookingapproved = BookingRequest::where('quotation_items_id', $quotationItem->id)->where('status', "Approved")->first();
                            if ($existingbookingpending) {
                                $existingbookingpending->days = "10";
                                $existingbookingpending->save();
                            } else if ($existingbookingapproved) {
                                $existingbookingapproved->days = "10";
                                $existingbookingapproved->save();
                                $updatebooking = Booking::where('booking_requests_id', $existingbookingapproved->id)->whereDate('booking_end_date', '>', now())->first();
                                if ($updatebooking) {
                                    $updatebooking->booking_end_date  = Carbon::now()->addDays(10);
                                    $updatebooking->save();
                                    $vehicle->reservation_end_date = Carbon::now()->addDays(10);
                                    $vehicle->save();
                                } else {
                                    $booking = new BookingRequest();
                                    $booking->vehicle_id = $vehicleId;
                                    $booking->calls_id = $calls->id;
                                    $booking->created_by = Auth::id();
                                    $booking->status = "New";
                                    $booking->days = "10";
                                    $booking->save();
                                }
                            } else {
                                $booking = new BookingRequest();
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

                $solog = new Solog();
                $solog->time = now()->format('H:i:s');
                $solog->date = now()->format('Y-m-d');
                $solog->status = 'SO Created';
                $solog->created_by = Auth::id();
                $solog->so_id = $so->id;
                $solog->role = Auth::user()->selectedRole;
                $solog->save();

                // add the file into quotaion files also
                $quotationFile = new QuotationFile();
                $quotationFile->quotation_id = $quotation->id;
                $file = $quotation->file_path;
                $filename = Str::after($file, 'quotation_files/');
                $quotationFile->file_name = $filename;
                $quotationFile->save();
             
            } else {
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

    public function updatesalesorder($id)
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
                            ->when(!$hasPermission, function ($query) use ($sodetails) {
                                $query->where(function ($subQuery) use ($sodetails) {
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
                                ->when(!$hasPermission, function ($query) use ($sodetails) {
                                    $query->where(function ($subQuery) use ($sodetails) {
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
                                ->when(!$hasPermission, function ($query) use ($sodetails) {
                                    $query->where(function ($subQuery) use ($sodetails) {
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
        foreach ($quotationItems as $quotationItem) {
            $selectedVehicleIds = $quotationItem->soItems->pluck('vehicles_id')->toArray();
            $quotationItem->selectedVehicleIds = $selectedVehicleIds;
            // check the quotation referenceid
            $quotationItem->isgdnExist = 0;
            foreach ($selectedVehicleIds as $eachVehicle) {
                $eachVehicle = Vehicles::find($eachVehicle);
                if ($eachVehicle->gdn_id) {
                    $quotationItem->isgdnExist = 1;
                    break;
                }
            }
        }
        $totalVehicles = $quotationItems->sum('quantity');
        $variants = Varaint::select('id', 'name')->get();
        // return $quotationItems;
        return view('salesorder.update', compact('vehicles', 'variants', 'totalVehicles', 'quotationItems', 'quotation', 'calls', 'customerdetails', 'so', 'soitems', 'empProfile', 'saleperson'));
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
            \Log::info('SO items deleted - Case 3-' . $so->id);
            Soitems::where('so_id', $so->id)->update(['deleted_by' => Auth::id()]);
            Soitems::where('so_id', $so->id)->delete();
            Vehicles::where('so_id', $so->id)->update(['so_id' => null]);
            \Log::info('Unassign SO id - Case 3-' . $so->id);
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
                    // chcek for booking request existing for vin => if yes update days 10 
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
            $solog = new Solog();
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
        DB::beginTransaction();
        try {
            $so = SO::find($id);
            if (!$so) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Sales Order not found.');
            }

            // Get related quotation and call and change status of quotation to show the relevent quottaion to list in quotaions
            $quotation = Quotation::where('id', $so->quotation_id)->first();
            if($quotation){
                $call = Calls::find($quotation->calls_id);
                $call->status = 'Quoted';
                $call->save();
            }
            $calls = $quotation ? Calls::find($quotation->calls_id) : null;

            // 1. Unassign vehicles
            Vehicles::where('so_id', $so->id)->update([
                'so_id' => null,
                'reservation_start_date' => null,
                'reservation_end_date' => null,
                'booking_person_id' => null,
            ]);

            // 2. Delete SalesOrderHistoryDetail and SalesOrderHistory
            // $historyIds = SalesOrderHistory::where('so_id', $so->id)->pluck('id');
            // SalesOrderHistoryDetail::whereIn('sales_order_history_id', $historyIds)->delete();
            // SalesOrderHistory::where('so_id', $so->id)->delete();

            //3.  Delete SoVariant
            $soVariantIds = SoVariant::where('so_id', $so->id)->pluck('id');
            // SoVariant::where('so_id', $so->id)->delete();

            // 4. Delete Soitems
            Soitems::whereIn('so_variant_id', $soVariantIds)->delete();

            // 5. Delete the closed leads
            Closed::where('so_id', $so->id)->delete();

            // 6. Set BookingRequest status to 'Rejected'
            if ($calls) {
                BookingRequest::where('calls_id', $calls->id)->update(['status' => 'Rejected']);
            }

            // 7. update so status (unable to make soft deletes due to so_id is linked to softdeleted data)
            $so->status = "Cancelled";
            $so->save();

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
                ->where(function ($query) use ($totalSales) {
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

    public function getVins(Request $request)
    {
        $data = [];
        $data['vehicles'] = Vehicles::where('varaints_id', $request->variant_id)
            ->whereNull('gdn_id')
            ->wherenotNull('vin')
            ->whereNotIn('id', $request->selectedVinIds ?? [])
            ->where(function ($query) use ($request) {
                $query->whereNull('so_id')
                    ->orWhere('so_id', $request->so_id);
            })
            ->select('id', 'vin')->get();

        $variant = Varaint::find($request->variant_id);
        $data['variant_description'] = ($variant->brand->brand_name ?? '') . ',' . ($variant->model_detail ?? '');

        return response()->json($data);
    }

    public function getVariants(Request $request)
    {

        $selectedVariantIds = $request->selectedVariantIds;
        $variants = Varaint::when(!empty($selectedVariantIds), function ($query) use ($selectedVariantIds) {
            $query->whereNotIn('id', $selectedVariantIds);
        })
            ->select('id', 'name')
            ->get();

        return response()->json($variants);
    }

    public function checkUniqueSoNumber(Request $request)
    {
        $exists = SO::where('so_number', $request->so_number)
            ->when($request->filled('so_id'), function ($query) use ($request) {
                return $query->where('id', '!=', $request->so_id);
            })
           ->where(function ($query) {
                $query->where('status', '!=', 'Cancelled')->orWhereNull('status');
            })
            ->whereDoesntHave('so_logs', function ($query) {
                $query->where('status', 'SO Cancel');
            })
            ->exists();
        // passing $request->so_id available  and except the id as well in edit page
        return response()->json(['exists' => $exists]);
    }

    public function approveOrRejectSO(Request $request)
    {
        $so = SO::findOrFail($request->so_id);
        $quotation = Quotation::findOrFail($so->quotation_id);
        $status = $request->status;
        if ($status == 'Approved') {
            $file = $this->generateLatestQuotation($so->id);
            $quotation->file_path = 'quotation_files/' . $file;
            $quotation->save();
            $so->status = 'Approved';
            $so->save();
        } else {
            $so->status = 'Rejected';
            $so->rejection_reason = $request->reason ?? '';
            $so->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Sales Order ' . $status . ' successfully.',
            'redirect' => route('salesorder.index')
        ]);
    }
}
