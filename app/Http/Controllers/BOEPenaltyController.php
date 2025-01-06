<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WOVehicles;
use App\Models\BOEPenalty;
use App\Models\BOEPenaltyType;
use App\Models\WOBOE;
use App\Models\Masters\PenaltyTypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BOEPenaltyController extends Controller
{
    public function getClearedPenalties() {
        try {
            $datas = WOBOE::whereHas('penalty.penaltyTypes.penaltyTypesName')
            ->with('penalty.penaltyTypes.penaltyTypesName')
            ->get();
            (new UserActivityController)->createActivity('Open Penalty Cleared BOE Listing');
            return view('work_order.penalty.cleared', compact('datas'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error fetching cleared penalty boe information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Show a friendly error page
            return response()->view('errors.generic', [], 500); // Return a 500 error page
        }
    }
    public function storeOrUpdate(Request $request)
    {
        $authId = Auth::id(); // Get the current authenticated user ID

        DB::beginTransaction(); // Begin transaction

        try {
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'wo_boe_id_') === 0) {
                    $woBOEId = $value;
                    // Define validation rules for each unique wo_boe_id
                    $validationRules = [
                        "wo_boe_{$woBOEId}" => 'required|string',
                        "invoice_date_{$woBOEId}" => 'required|date',
                        "invoice_number_{$woBOEId}" => 'required|string|max:100',
                        "penalty_amount_{$woBOEId}" => 'required|numeric|min:0',
                        "penalty_type_{$woBOEId}" => 'required|array',
                        "payment_receipt_{$woBOEId}" => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                        "remarks" => 'nullable|string|max:500',
                    ];

                    $validatedData = $request->validate($validationRules); // Validate data
                    // Handle file upload
                    $imageName = null;
                    if ($request->hasFile("payment_receipt_{$woBOEId}")) {
                        $image = $request->file("payment_receipt_{$woBOEId}");
                        $imageName = $validatedData["wo_boe_{$woBOEId}"] . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('work_order/boe_penalty_receipt'), $imageName);
                    }   
                    // Prepare remarks
                    $remarks = trim($validatedData['remarks'] ?? '') === '' ? null : $validatedData['remarks'];   
                    // Prepare data for the BOEPenalty record
                    $penaltyData = [
                        'wo_boe_id' => $woBOEId,
                        'invoice_date' => $validatedData["invoice_date_{$woBOEId}"],
                        'invoice_number' => $validatedData["invoice_number_{$woBOEId}"],
                        'penalty_amount' => $validatedData["penalty_amount_{$woBOEId}"],
                        'remarks' => $remarks,
                        'updated_by' => $authId,
                        'payment_receipt' => $imageName,
                    ];
                    // Check if a penalty record exists
                    $penalty = BOEPenalty::where('wo_boe_id', $woBOEId)->first();
                    if ($penalty) {
                        // Update existing record
                        $penalty->update($penaltyData);
                    } else {
                        // Insert new record
                        $penaltyData['created_by'] = $authId;
                        $penalty = BOEPenalty::create($penaltyData);
                    }
                    // Store penalty types
                    $penaltyTypeIds = $validatedData["penalty_type_{$woBOEId}"];
                    foreach ($penaltyTypeIds as $penaltyTypeId) {
                        BOEPenaltyType::updateOrCreate(
                            [
                                'boe_penalties_id' => $penalty->id,
                                'penalty_types_id' => $penaltyTypeId,
                            ],
                            []
                        );
                    }
                }
            }
            (new UserActivityController)->createActivity('Penalty Info added');
            DB::commit();
            return redirect()->route('getBOEPenaltyReport')->with('success', 'Penalty information saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error saving penalty information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            // Show a friendly error page
            return response()->view('errors.generic', [], 500); // Return a 500 error page
        }
    }

    public function getBOEPenaltyReport() {
        try {
            $today = Carbon::today();
            // Get all `BOE` where the declaration date is 29 days ago or earlier       
            $BOE = WOBOE::where('declaration_date', '<=', $today->copy()->subDays(29))
                ->whereDoesntHave('penalty') // Exclude BOE that already have a penalty record
                ->whereHas('workOrder')      // Ensure workOrder exists
                ->with(['workOrder'])        // Eager load workOrder
                ->get();
            // Filter out BOE with 'Delivered' status in PHP (since it's an appended attribute)
            $datas = $BOE->filter(function ($oneBOE) {
                return $oneBOE->workOrder && $oneBOE->workOrder->delivery_summary !== 'DELIVERED WITH DOCUMENTS';
            });
            $penaltyTypes = PenaltyTypes::where('is_active',true)->select('id','name')->get();
            (new UserActivityController)->createActivity('Open Penalized BOE Listing');
            return view('work_order.penalty.index', compact('datas','penaltyTypes'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error fetching cleared penalty boe information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Show a friendly error page
            return response()->view('errors.generic', [], 500); // Return a 500 error page
        }
    }  
    public function getNoPenalties() {
        try {
            $today = Carbon::today();
        
            // Get all `BOE` where the declaration date is 29 days ago or earlier
            $BOE = WOBOE::whereHas('workOrder')
                ->whereDoesntHave('penalty') // Exclude BOE that already have a penalty record
                ->with('workOrder')   // Eager load woBoe and its nested workOrder relationship
                ->get();
        
            // Filter out BOE with 'Delivered' status in PHP (since it's an appended attribute)
            $datas = $BOE->filter(function ($oneBOE) {
                return $oneBOE->workOrder && $oneBOE->workOrder->delivery_summary !== 'DELIVERED WITH DOCUMENTS';
            });
            (new UserActivityController)->createActivity('Open No Penalty BOE Listing');
            return view('work_order.penalty.no_penalty', compact('datas'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error fetching no penalties boe information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Show a friendly error page
            return response()->view('errors.generic', [], 500); // Return a 500 error page
        }
    } 
}
