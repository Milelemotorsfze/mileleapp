<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WOVehicles;
use App\Models\WOBOEClaims;
use App\Models\WOBOE;
use App\Models\WOBOEClaimShippingDetail;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WOBOEClaimsController extends Controller
{
    public function getPendingClaims() { 
        try {
            $boes = WOBOE::select('id', 'wo_id','boe', 'declaration_number','declaration_date')->with(['claim', 'workOrder', 'workOrder.vehicles', 'shippingDetails.finalDestinationCountry']) 
            ->where(function($query) {
                // Condition 1: No associated claim
                $query->whereDoesntHave('claim')
                    // Condition 2: Or the latest associated claim has a 'Cancelled' status
                    ->orWhereHas('claim', function($q) {
                        $q->where('status', 'Cancelled')
                            ->whereRaw('id = (SELECT id FROM wo_boe_claims WHERE wo_boe_id = wo_boe.id ORDER BY updated_at DESC LIMIT 1)');
                    });
            })
            // New condition: declaration_number and declaration_date should not be null
            ->whereNotNull('declaration_number')
            ->whereNotNull('declaration_date')
            ->get();
            // Filter out vehicles with 'Delivered' status in PHP (since it's an appended attribute)
            $datas = $boes->filter(function ($boe) { 
                return isset($boe->workOrder) 
                && $boe->workOrder->has_claim === 'yes'
                    && $boe->workOrder->delivery_summary !== 'DELIVERED WITH DOCUMENTS' 
                    && $boe->workOrder->sales_support_data_confirmation === 'Confirmed'
                    && $boe->workOrder->finance_approval_status === 'Approved' 
                    && $boe->workOrder->coo_approval_status === 'Approved'
                    ; // Only keep vehicles with non-delivered status
            });  
            $countries = Country::select('id','name')->orderBy('name')->get();
            (new UserActivityController)->createActivity('Open Claim Pending BOE Listing');
            return view('work_order.claims.index', compact('datas','countries'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error fetching claim pending boe information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Show a friendly error page
            return response()->view('errors.generic', [], 500); // Return a 500 error page
        }
    }
    public function getSubmittedClaims() {
        try {
            $datas = WOBOE::whereHas('claim', function($q) {
                $q->where('status','Submitted')
                ->whereRaw('id = (SELECT id FROM wo_boe_claims WHERE wo_boe_id = wo_boe.id ORDER BY updated_at DESC LIMIT 1)');
            })->with(['claim.createdUser', 'workOrder', 'workOrder.vehicles', 'shippingDetails.finalDestinationCountry'])->get();
            (new UserActivityController)->createActivity('Open Claim Submitted BOE Listing');
            return view('work_order.claims.submitted', compact('datas'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error fetching submitted claim boe information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Show a friendly error page
            return response()->view('errors.generic', [], 500); // Return a 500 error page
        }
    }
    public function getApprovedClaims() { 
        try {
            $datas = WOBOE::whereHas('claim', function($q) {
                $q->where('status', 'Approved')
                ->whereIn('id', function($query) {
                    $query->selectRaw('MAX(id)')
                            ->from('wo_boe_claims')
                            ->groupBy('wo_boe_id');
                });
            })->with(['claim.createdUser', 'workOrder', 'workOrder.vehicles', 'shippingDetails.finalDestinationCountry'])->get();
            (new UserActivityController)->createActivity('Open Claim Approved BOE Listing');
            return view('work_order.claims.approved', compact('datas'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error fetching approved claim boe information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Show a friendly error page
            return response()->view('errors.generic', [], 500); // Return a 500 error page
        }
    }
    public function getCancelledClaims() { 
        try {
            $datas = WOBOE::whereHas('claim', function($q) {
                $q->where('status','Cancelled')
                ->whereRaw('id = (SELECT id FROM wo_boe_claims WHERE wo_boe_id = wo_boe.id ORDER BY updated_at DESC LIMIT 1)');
            })->with(['claim.createdUser', 'workOrder', 'workOrder.vehicles', 'shippingDetails.finalDestinationCountry'])->get();
            (new UserActivityController)->createActivity('Open Claim Cancelled BOE Listing');
            return view('work_order.claims.cancelled', compact('datas'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            // Log the error
            Log::channel('workorder_error_report')->error('Error fetching cancelled claim boe information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
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
                    $WOBOEID = $value;
    
                    // Define validation rules for each unique wo_boe_id
                    $validationRules = [
                        "claim_date_{$WOBOEID}" => 'required|date',
                        "claim_reference_number_{$WOBOEID}" => 'nullable|integer|min:1',
                        "status_{$WOBOEID}" => 'nullable|string|in:Submitted,Approved,Cancelled',
                    ];
    
                    $validatedData = $request->validate($validationRules); // Validate data
    
                    // Prepare data for the record, conditionally including `created_by`
                    $claimsData = [
                        'wo_boe_id' => $WOBOEID,
                        'claim_date' => $validatedData["claim_date_{$WOBOEID}"],
                        'claim_reference_number' => $validatedData["claim_reference_number_{$WOBOEID}"] ?? 0,
                        'status' => $validatedData["status_{$WOBOEID}"] ?? '',
                        'updated_by' => $authId,
                    ];
                    $claimsData['created_by'] = $authId;
                    $claims = WOBOEClaims::create($claimsData);

                    // Additive: per-VIN shipping details entered on the same modal.
                    $this->saveClaimShippingDetails($request, $WOBOEID, $claims->id, $authId);
                }
            }
            (new UserActivityController)->createActivity('claims Info added');
            DB::commit(); // Commit transaction
    
            return redirect()->route('getPendingClaims')->with('success', 'claims information saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            Log::channel('workorder_error_report')->error('Error saving claims information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
    
            return redirect()->route('getPendingClaims')->withErrors('An error occurred while saving claims information.');
        }
    }
    
    public function updateStatus(Request $request) {
        $authId = Auth::id(); // Get the current authenticated user ID
    
        DB::beginTransaction(); // Begin transaction
    
        try {
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'wo_boe_id_') === 0) {
                    $woVehicleId = $value;
    
                    // Define validation rules for each unique wo_boe_id
                    $validationRules = [
                        "status_{$woVehicleId}" => 'nullable|string|in:Approved,Cancelled',
                    ];
    
                    $validatedData = $request->validate($validationRules); // Validate data
    
                    // Prepare data for the record, conditionally including `created_by`
                    $claimsData = [
                        'wo_boe_id' => $woVehicleId,
                        'status' => $validatedData["status_{$woVehicleId}"] ?? '',
                        'updated_by' => $authId,
                    ];
    
                    // Use `updateOrCreate` to update existing or insert new, with `created_by` only for new entries
                    $claims = WOBOEClaims::where('wo_boe_id', $woVehicleId)->first();
    
                    if ($claims) {
                        // Update existing record
                        $claims->update($claimsData);
                    }
                }
            }
            (new UserActivityController)->createActivity('claims status updated');
            DB::commit(); // Commit transaction
    
            return redirect()->route('getSubmittedClaims')->with('success', 'claims status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            Log::channel('workorder_error_report')->error('Error updating claims status by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
    
            return redirect()->route('getSubmittedClaims')->withErrors('An error occurred while updating claims status.');
        }
    }
    /**
     * Persist the per-VIN shipping details captured alongside a claim.
     *
     * Claim side only - nothing here reads or writes the work order level
     * container_number / final_destination columns. Runs inside the caller's
     * transaction so it commits or rolls back with the claim itself.
     */
    private function saveClaimShippingDetails(Request $request, $woBoeId, $claimId, $authId)
    {
        // Same permission that gates the Update Claim modal itself.
        if (!Auth::check() || !Auth::user()->hasPermissionForSelectedRole(['can-update-vehicle-claims'])) {
            return;
        }

        $rows = $request->input("shipping_details.{$woBoeId}");
        if (!is_array($rows) || count($rows) === 0) {
            return;
        }

        $boe = WOBOE::find($woBoeId);
        if (!$boe) {
            return;
        }

        // VIN rows that genuinely belong to this BOE's work order. A posted id
        // outside this set is ignored, so details can never be attached to a
        // vehicle on another work order.
        $allowedVehicleIds = WOVehicles::where('work_order_id', $boe->wo_id)
            ->pluck('id')
            ->map(function ($id) { return (int) $id; })
            ->all();

        foreach ($rows as $woVehicleId => $row) {
            $woVehicleId = (int) $woVehicleId;
            if (!in_array($woVehicleId, $allowedVehicleIds, true) || !is_array($row)) {
                continue;
            }

            $validated = validator($row, [
                'container_number' => 'nullable|string|max:100',
                'bl_number' => 'nullable|string|max:100',
                'final_destination_country_id' => 'nullable|integer|exists:countries,id',
            ])->validate();

            $container = trim((string) ($validated['container_number'] ?? ''));
            $blNumber = trim((string) ($validated['bl_number'] ?? ''));
            $countryId = $validated['final_destination_country_id'] ?? null;

            $container = $container === '' ? null : $container;
            $blNumber = $blNumber === '' ? null : $blNumber;
            $countryId = ($countryId === '' || $countryId === null) ? null : (int) $countryId;

            $existing = WOBOEClaimShippingDetail::where('wo_boe_id', $woBoeId)
                ->where('w_o_vehicle_id', $woVehicleId)
                ->first();

            // Nothing entered and nothing stored - don't create an empty row, so
            // "never entered" stays distinguishable from "entered and cleared".
            if (!$existing && is_null($container) && is_null($blNumber) && is_null($countryId)) {
                continue;
            }

            $payload = [
                'wo_boe_claim_id' => $claimId,
                'container_number' => $container,
                'bl_number' => $blNumber,
                'final_destination_country_id' => $countryId,
                'updated_by' => $authId,
            ];

            if ($existing) {
                $existing->update($payload);
            } else {
                $payload['wo_boe_id'] = $woBoeId;
                $payload['w_o_vehicle_id'] = $woVehicleId;
                $payload['created_by'] = $authId;
                WOBOEClaimShippingDetail::create($payload);
            }
        }
    }
    public function getClaimsLog($id) {
        info('hi');
    }
}
