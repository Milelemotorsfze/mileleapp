<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use App\Models\WOVehicleStatus;
use App\Models\WorkOrder;
use App\Models\WOVehicles;
use App\Models\Masters\MasterOfficeLocation;


class WoVehicleController extends Controller
{
    public function updateVehModiStatus(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'woVehicleId' => 'required|exists:w_o_vehicles,id', // Reference to w_o_vehicle_id
            'status' => 'required|in:Not Initiated,Initiated,Completed',
            'comment' => 'nullable|string',
            'expected_completion_datetime' => 'nullable|date', // Ensure the datetime field is a valid date
            'current_vehicle_location' => 'nullable|string', // Ensure current vehicle location is a string
            'vehicle_available_location' => 'nullable|exists:master_office_locations,id', // Ensure it references a valid location
        ]);

        // Always create a new status_tracking record
        $statusTracking = WOVehicleStatus::create([
            'w_o_vehicle_id' => $validatedData['woVehicleId'], // Associate with the work order vehicle ID
            'user_id' => Auth::id(), // Set the ID of the authenticated user
            'status' => $validatedData['status'], // Set the status
            'comment' => $validatedData['comment'], // Optional comment
            'expected_completion_datetime' => $validatedData['expected_completion_datetime'] ?? null, // Optional, set to null if not provided
            'current_vehicle_location' => $validatedData['current_vehicle_location'] ?? null, // Optional, set to null if not provided
            'vehicle_available_location' => $validatedData['vehicle_available_location'] ?? null, // Optional, set to null if not provided
        ]);

        // Fetch the work order vehicle
        $woVehicle = WOVehicles::findOrFail($validatedData['woVehicleId']);
        
        // Sync location change to vehicles table if vehicle_available_location was updated
        if (isset($validatedData['vehicle_available_location']) && $validatedData['vehicle_available_location']) {
            $this->syncLocationToVehicles($woVehicle, $validatedData['vehicle_available_location']);
        }
        $workOrder = $woVehicle->workOrder; // Assuming a relationship exists from `WoVehicle` to `WorkOrder`

        // Only send an email if the status is "Completed"
        if ($validatedData['status'] === 'Completed') {
            // Prepare email template details
            $template = [
                'from' => 'no-reply@milele.com',
                'from_name' => 'Milele Matrix',
            ];
            // Handle cases where customer_name is null
            $customerName = $workOrder->customer_name ?? 'Unknown Customer';
            $statusName = $validatedData['status'];
            // Prepare email subject
            $subject = "Work Order Vehicle Modification Status changed to $statusName for Vehicle VIN: " . $woVehicle->vin . " under Work Order " . $workOrder->wo_number;
            // Retrieve the authenticated user's name
            $authUserName = auth()->user()->name;
            // Define quick access links
            $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
            $statusLogLink = env('BASE_URL') . '/vehicle-modification-status-log/' . $woVehicle->id;
            // Retrieve email addresses from the users table where can_send_wo_email is true
            $managementEmails = \App\Models\User::where('can_send_wo_email', true)->pluck('email')->filter(function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })->toArray();
            // Retrieve and validate email addresses for other recipients
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL);
            $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
            $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);
            // Fetch `DONT_SEND_EMAIL` and `REDIRECT_SALES_EMAIL_TO` from .env
            $dontSendEmail = env('DONT_SEND_EMAIL');
            $redirectSalesEmailTo = filter_var(env('REDIRECT_SALES_EMAIL_TO'), FILTER_VALIDATE_EMAIL);
            // Log email addresses to debug
            \Log::info('Email Recipients:', [
                'managementEmails' => $managementEmails,
                'operationsEmail' => $operationsEmail,
                'createdByEmail' => $createdByEmail,
                'salesPersonEmail' => $salesPersonEmail,
            ]);
            // Check if the salesPerson exists before trying to access properties
            $shouldSendToSalesPerson = false;
            if ($workOrder->salesPerson) {
                $shouldSendToSalesPerson = $createdByEmail !== $salesPersonEmail
                    && $workOrder->salesPerson->is_sales_rep === 'Yes'
                    && $workOrder->salesPerson->is_management === 'No';
            }
            // Initialize recipient list with management emails from the database
            $recipients = array_filter(array_merge($managementEmails, [$operationsEmail]));
            // Add createdByEmail if valid
            if ($createdByEmail) {
                $recipients[] = $createdByEmail;
            }
            // Handle salesPersonEmail conditions
            if ($shouldSendToSalesPerson) {
                if ($salesPersonEmail && $salesPersonEmail !== $dontSendEmail) {
                    // If salesperson's email is not blocked, add it
                    $recipients[] = $salesPersonEmail;
                } elseif ($salesPersonEmail === $dontSendEmail && $redirectSalesEmailTo) {
                    // Redirect email if salesPersonEmail matches DONT_SEND_EMAIL
                    $recipients[] = $redirectSalesEmailTo;
                }
            }
            // If no valid recipients, skip sending the email and log the issue
            if (empty($recipients)) {
                \Log::info('No valid recipients found. Skipping email sending for WO-' . $workOrder->wo_number);
                return; // Exit the function without throwing an exception
            }
            // Send email using a Blade template
            Mail::send('work_order.emails.modification_status_update', [
                'workOrder' => $workOrder,
                'woVehicle' => $woVehicle,
                'accessLink' => $accessLink,
                'statusLogLink' => $statusLogLink,
                'comments' => $validatedData['comment'],
                'statusTracking' => $statusTracking,
                'userName' => $authUserName,
                'status' => $statusName,
                'datetime' => Carbon::now(),
            ], function ($message) use ($subject, $recipients, $template) {
                $message->from($template['from'], $template['from_name'])
                        ->to($recipients)
                        ->subject($subject);
            });
            // Log successful email sending
            \Log::info('Email sent to recipients:', $recipients);
        }

        // Return a JSON response indicating success
        return response()->json(['message' => 'Status updated successfully', 'data' => $statusTracking]);
    }

    public function vehModiStatusHistory($woVehicleId)
    {
        // Fetch the status history for the specific vehicle from status_tracking
        $data = WOVehicleStatus::where('w_o_vehicle_id', $woVehicleId)->orderBy('created_at', 'DESC')->get();

        // Fetch the associated work order vehicle and work order
        $vehicle = WOVehicles::findOrFail($woVehicleId);
        $workOrder = $vehicle->workOrder; // Assuming WoVehicle belongs to WorkOrder
        $type = $workOrder->type;

        // Fetch the previous and next work orders by type
        $previous = WorkOrder::where('type', $type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type', $type)->where('id', '>', $workOrder->id)->min('id');
        $locations = MasterOfficeLocation::select('id','name')->get();

        // Return the view with the status history and navigation data
        return view('work_order.export_exw.wo-vehicle-status-history', compact('data', 'type', 'woVehicleId', 'previous', 'next', 'workOrder', 'vehicle','locations'));
    }
    public function fetchBoeNumber(Request $request)
    {
        $workOrderId = $request->input('work_order_id');
        // Check the WOVehicles model for the given work_order_id and fetch unique boe_number
        $boeNumber = WOVehicles::where('work_order_id', $workOrderId)
                    ->distinct()
                    ->value('boe_number');
        if ($boeNumber) {
            return response()->json([
                'success' => true,
                'boe_number' => $boeNumber
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No unique boe_number found.'
            ]);
        }
    }

    /**
     * Sync location change from Work Order to vehicles table
     */
    private function syncLocationToVehicles($woVehicle, $masterLocationId)
    {
        try {
            // Get the master office location
            $masterLocation = MasterOfficeLocation::find($masterLocationId);
            if (!$masterLocation) {
                \Log::warning('Master office location not found for ID: ' . $masterLocationId);
                return;
            }

            // Find corresponding warehouse by name
            $warehouse = \App\Models\Warehouse::where('name', $masterLocation->name)->first();
            if (!$warehouse) {
                \Log::warning('Warehouse not found for master location: ' . $masterLocation->name);
                return;
            }

            // Update the vehicle's latest_location
            $vehicle = $woVehicle->vehicle;
            if ($vehicle) {
                $oldLocation = $vehicle->latest_location;
                $vehicle->update(['latest_location' => $warehouse->id]);
                
                \Log::info('Synced location from Work Order Vehicle Status to vehicles table', [
                    'vehicle_id' => $vehicle->id,
                    'vin' => $vehicle->vin,
                    'wo_vehicle_id' => $woVehicle->id,
                    'old_location' => $oldLocation,
                    'new_location' => $warehouse->id,
                    'warehouse_name' => $warehouse->name,
                    'master_location_name' => $masterLocation->name,
                    'updated_by' => auth()->user()->email ?? 'system'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Error syncing location from Work Order Vehicle Status to vehicles', [
                'wo_vehicle_id' => $woVehicle->id,
                'master_location_id' => $masterLocationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
