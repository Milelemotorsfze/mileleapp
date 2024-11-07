<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WOVehicles;
use App\Models\WOVehicleClaims;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WOVehicleClaimsController extends Controller
{
    public function getPendingClaims() {
        $vehicles = WOVehicles::select('id','vin','work_order_id')->whereDoesntHave('claim')->whereHas('woBoe')->get();
    
        // Filter out vehicles with 'Delivered' status in PHP (since it's an appended attribute)
        $datas = $vehicles->filter(function ($vehicle) {
            return $vehicle->delivery_status !== 'Delivered' && $vehicle->workOrder->sales_support_data_confirmation === 'Confirmed'
                && $vehicle->workOrder->finance_approval_status === 'Approved' && $vehicle->workOrder->coo_approval_status === 'Approved';  // Only keep vehicles with non-delivered status
        });
        (new UserActivityController)->createActivity('Open Claim Pending Listing');
        return view('work_order.claims.index', compact('datas'));
    }
    public function getSubmittedClaims() {
        $datas = WOVehicles::whereHas('claim', function($q) {
            $q->where('status','Submitted');
        })->get();
        (new UserActivityController)->createActivity('Open Claim Submitted Vehicles Listing');
        return view('work_order.claims.submitted', compact('datas'));
    }
    public function getApprovedClaims() {
        $datas = WOVehicles::whereHas('claim', function($q) {
            $q->where('status','Approved');
        })->get();
        (new UserActivityController)->createActivity('Open Claim Approved Vehicles Listing');
        return view('work_order.claims.approved', compact('datas'));
    }
    public function getCancelledClaims() {
        $datas = WOVehicles::whereHas('claim', function($q) {
            $q->where('status','Cancelled');
        })->get();
        (new UserActivityController)->createActivity('Open Claim Cancelled Vehicles Listing');
        return view('work_order.claims.cancelled', compact('datas'));
    }
    public function storeOrUpdate(Request $request)
    {
        $authId = Auth::id(); // Get the current authenticated user ID
    
        DB::beginTransaction(); // Begin transaction
    
        try {
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'wo_vehicle_id_') === 0) {
                    $woVehicleId = $value;
    
                    // Define validation rules for each unique wo_vehicle_id
                    $validationRules = [
                        "claim_date_{$woVehicleId}" => 'required|date',
                        "claim_reference_number_{$woVehicleId}" => 'nullable|integer|min:1',
                        "status_{$woVehicleId}" => 'nullable|string|in:Submitted,Approved,Cancelled',
                    ];
    
                    $validatedData = $request->validate($validationRules); // Validate data
    
                    // Prepare data for the record, conditionally including `created_by`
                    $claimsData = [
                        'wo_vehicle_id' => $woVehicleId,
                        'claim_date' => $validatedData["claim_date_{$woVehicleId}"],
                        'claim_reference_number' => $validatedData["claim_reference_number_{$woVehicleId}"] ?? 0,
                        'status' => $validatedData["status_{$woVehicleId}"] ?? '',
                        'updated_by' => $authId,
                    ];
                    $claimsData['created_by'] = $authId;
                    $claims = WOVehicleClaims::create($claimsData);
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
                if (strpos($key, 'wo_vehicle_id_') === 0) {
                    $woVehicleId = $value;
    
                    // Define validation rules for each unique wo_vehicle_id
                    $validationRules = [
                        "status_{$woVehicleId}" => 'nullable|string|in:Approved,Cancelled',
                    ];
    
                    $validatedData = $request->validate($validationRules); // Validate data
    
                    // Prepare data for the record, conditionally including `created_by`
                    $claimsData = [
                        'wo_vehicle_id' => $woVehicleId,
                        'status' => $validatedData["status_{$woVehicleId}"] ?? '',
                        'updated_by' => $authId,
                    ];
    
                    // Use `updateOrCreate` to update existing or insert new, with `created_by` only for new entries
                    $claims = WOVehicleClaims::where('wo_vehicle_id', $woVehicleId)->first();
    
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
}
