<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WOVehicles;
use App\Models\WOBOEClaims;
use App\Models\WOBOE;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WOBOEClaimsController extends Controller
{
    public function getPendingClaims() { 
        try {
            $boes = WOBOE::select('id', 'wo_id','boe', 'declaration_number','declaration_date')->with(['claim', 'workOrder']) 
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
            (new UserActivityController)->createActivity('Open Claim Pending BOE Listing');
            return view('work_order.claims.index', compact('datas'));
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
            })->get();
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
            })->get();
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
            })->get();
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
    public function getClaimsLog($id) {
        info('hi');
    }
}
