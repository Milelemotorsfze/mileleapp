<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WOVehicles;
use App\Models\VehiclePenalty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VehiclePenaltyController extends Controller
{
    public function getClearedPenalties() {
        $datas = WOVehicles::whereHas('penalty')->get();
        (new UserActivityController)->createActivity('Open Penalty Cleared Vehicles Listing');
        return view('work_order.penalty.cleared', compact('datas'));
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
                        "payment_date_{$woVehicleId}" => 'required|date',
                        "excess_days_{$woVehicleId}" => 'nullable|integer|min:1',
                        "total_penalty_amount_{$woVehicleId}" => 'nullable|numeric|min:0',
                        "amount_paid_{$woVehicleId}" => "nullable|numeric|min:0|lte:total_penalty_amount_{$woVehicleId}",
                        "payment_receipt_{$woVehicleId}" => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                        "remarks" => 'nullable|string|max:500',
                    ];

                    $validatedData = $request->validate($validationRules); // Validate data

                    // Prepare data for the record, conditionally including `created_by`
                    $penaltyData = [
                        'wo_vehicle_id' => $woVehicleId,
                        'payment_date' => $validatedData["payment_date_{$woVehicleId}"],
                        'excess_days' => $validatedData["excess_days_{$woVehicleId}"] ?? 0,
                        'total_penalty_amount' => $validatedData["total_penalty_amount_{$woVehicleId}"] ?? 0,
                        'amount_paid' => $validatedData["amount_paid_{$woVehicleId}"] ?? 0,
                        'remarks' => $validatedData['remarks'] ?? '',
                        'updated_by' => $authId,
                    ];

                    // Use `updateOrCreate` to update existing or insert new, with `created_by` only for new entries
                    $penalty = VehiclePenalty::where('wo_vehicle_id', $woVehicleId)->first();

                    if ($penalty) {
                        // Update existing record
                        $penalty->update($penaltyData);
                    } else {
                        // Insert new record, including `created_by`
                        $penaltyData['created_by'] = $authId;
                        $penalty = VehiclePenalty::create($penaltyData);
                    }

                    // Handle file upload if present
                    if ($request->hasFile("payment_receipt_{$woVehicleId}")) {
                        // Delete old file if it exists
                        if ($penalty->payment_receipt) {
                            Storage::delete($penalty->payment_receipt);
                        }

                        // Store new file and update record
                        $receiptPath = $request->file("payment_receipt_{$woVehicleId}")->store('payment_receipts');
                        $penalty->update(['payment_receipt' => $receiptPath]);
                    }
                }
            }
            (new UserActivityController)->createActivity('Penalty Info added');
            DB::commit(); // Commit transaction

            return redirect()->route('getVehiclePenaltyReport')->with('success', 'Penalty information saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of error
            Log::channel('workorder_error_report')->error('Error saving penalty information by ' . (Auth::check() ? Auth::user()->name : 'Guest'), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->route('getVehiclePenaltyReport')->withErrors('An error occurred while saving penalty information.');
        }
    }

    public function getVehiclePenaltyReport() {
        $today = Carbon::today();
    
        // Get all `vehicles` where the declaration date is 29 days ago or earlier
        $vehicles = WOVehicles::whereHas('woBoe', function ($query) use ($today) {
                // Filter by declaration_date from the related WOBOE records
                $query->where('declaration_date', '<=', $today->copy()->subDays(29));
            })
            ->whereDoesntHave('penalty') // Exclude vehicles that already have a penalty record
            ->with(['woBoe.workOrder'])   // Eager load woBoe and its nested workOrder relationship
            ->get();
    
        // Filter out vehicles with 'Delivered' status in PHP (since it's an appended attribute)
        $datas = $vehicles->filter(function ($vehicle) {
            return $vehicle->delivery_status !== 'Delivered';  // Only keep vehicles with non-delivered status
        });
        (new UserActivityController)->createActivity('Open Penalized Vehicles Listing');
        return view('work_order.penalty.index', compact('datas'));
    }    
}
