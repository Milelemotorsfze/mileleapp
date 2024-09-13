<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use App\Models\WoPDIStatus;
use App\Models\WorkOrder;
use App\Models\WoVehicles;

class WoPDIStatusController extends Controller
{
    public function updateVehPdiStatus(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'woVehicleId' => 'required|exists:w_o_vehicles,id', // Reference to w_o_vehicle_id
            'status' => 'required|in:Not Initiated,Scheduled,Completed',
            'comment' => 'nullable|string',
            'pdi_scheduled_at' => 'nullable|date', // Ensure the datetime field is a valid date
            'qc_inspector_remarks' => 'nullable|string', // Ensure current vehicle location is a string
            'passed_status' => 'in:Passed,Failed',
        ]);

        // Always create a new status_tracking record
        $statusTracking = WoPDIStatus::create([
            'w_o_vehicle_id' => $validatedData['woVehicleId'], // Associate with the work order vehicle ID
            'user_id' => Auth::id(), // Set the ID of the authenticated user
            'status' => $validatedData['status'], // Set the status
            'comment' => $validatedData['comment'], // Optional comment
            'pdi_scheduled_at' => $validatedData['pdi_scheduled_at'] ?? null, // Optional, set to null if not provided
            'qc_inspector_remarks' => $validatedData['qc_inspector_remarks'] ?? null, // Optional, set to null if not provided
            'passed_status' => $validatedData['passed_status'] ?? null, // Optional, set to null if not provided
        ]);

        // Fetch the work order vehicle
        $woVehicle = WoVehicles::findOrFail($validatedData['woVehicleId']);
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
            $subject = "Work Order Vehicle PDI Status changed to $statusName for Vehicle VIN: " . $woVehicle->vin . " under Work Order " . $workOrder->wo_number;

            // Retrieve the authenticated user's name
            $authUserName = auth()->user()->name;

            // Define a quick access link (adjust the route as needed)
            $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
            $statusLogLink = env('BASE_URL') . '/vehicle-pdi-status-log/' . $woVehicle->id;

            // Retrieve and validate email addresses from .env
            $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
            $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);

            // Log email addresses to help with debugging
            \Log::info('Email Recipients:', [
                'managementEmail' => $managementEmail,
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

            // Initialize recipient list
            $recipients = [$managementEmail, $operationsEmail, $createdByEmail];

            // Add salesPersonEmail only if the condition is met
            if ($shouldSendToSalesPerson && $salesPersonEmail) {
                $recipients[] = $salesPersonEmail;
            }

            // Log and handle invalid email addresses
            if (!$managementEmail || !$operationsEmail || !$createdByEmail) {
                \Log::error('Invalid email addresses provided:', [
                    'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
                    'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                    'createdByEmail' => $createdByEmail,
                ]);
                throw new \Exception('One or more email addresses are invalid.');
            }

            // Send email using a Blade template
            Mail::send('work_order.emails.pdi_status_update', [
                'workOrder' => $workOrder,
                'woVehicle' => $woVehicle,
                'accessLink' => $accessLink,
                'statusLogLink' => $statusLogLink,
                'comments' => $validatedData['comment'],
                'userName' => $authUserName,
                'status' => $statusName,
                'datetime' => Carbon::now(),
                'statusTracking' => $statusTracking,
            ], function ($message) use ($subject, $recipients, $template) {
                $message->from($template['from'], $template['from_name'])
                        ->to($recipients)
                        ->subject($subject);
            });
        }

        // Return a JSON response indicating success
        return response()->json(['message' => 'Status updated successfully', 'data' => $statusTracking]);
    }

    public function vehPdiStatusHistory($woVehicleId)
    {
        // Fetch the status history for the specific vehicle from status_tracking
        $data = WoPDIStatus::where('w_o_vehicle_id', $woVehicleId)->orderBy('created_at', 'DESC')->get();

        // Fetch the associated work order vehicle and work order
        $vehicle = WoVehicles::findOrFail($woVehicleId);
        $workOrder = $vehicle->workOrder; // Assuming WoVehicle belongs to WorkOrder
        $type = $workOrder->type;

        // Fetch the previous and next work orders by type
        $previous = WorkOrder::where('type', $type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type', $type)->where('id', '>', $workOrder->id)->min('id');

        // Return the view with the status history and navigation data
        return view('work_order.export_exw.pdi-status-history', compact('data', 'type', 'woVehicleId', 'previous', 'next', 'workOrder', 'vehicle'));
    }
}
