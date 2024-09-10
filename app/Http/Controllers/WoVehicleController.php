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
use App\Models\WoVehicles;
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
            $subject = "Work Order Vehicle Status changed to $statusName for Vehicle VIN: " . $woVehicle->vin . " under Work Order " . $workOrder->wo_number;

            // Retrieve the authenticated user's name
            $authUserName = auth()->user()->name;

            // Define a quick access link (adjust the route as needed)
            $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
            $statusLogLink = env('BASE_URL') . '/vehicle-modification-status-log/' . $woVehicle->id;

            // Retrieve and validate email addresses from .env
            $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
            $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);
            $customerEmail = filter_var($workOrder->customer_email, FILTER_VALIDATE_EMAIL);

            // Log email addresses to debug
            \Log::info('Email Recipients:', [
                'managementEmail' => $managementEmail,
                'operationsEmail' => $operationsEmail,
                'createdByEmail' => $createdByEmail,
                'salesPersonEmail' => $salesPersonEmail,
                'customerEmail' => $customerEmail,
            ]);

            // Check if the salesPerson exists before trying to access properties
            $shouldSendToSalesPerson = false;
            if ($workOrder->salesPerson) {
                $shouldSendToSalesPerson = $createdByEmail !== $salesPersonEmail
                    && $workOrder->salesPerson->is_sales_rep === 'Yes'
                    && $workOrder->salesPerson->is_management === 'No';
            }

            // Initialize recipient list
            $recipients = [$managementEmail, $operationsEmail];

            // Add createdByEmail if valid
            if ($createdByEmail) {
                $recipients[] = $createdByEmail;
            }

            // Add salesPersonEmail only if the condition is met
            if ($shouldSendToSalesPerson && $salesPersonEmail) {
                $recipients[] = $salesPersonEmail;
            }

            // Add customerEmail if valid
            if ($customerEmail) {
                $recipients[] = $customerEmail;
            }

            // Log and handle invalid email addresses
            if (!$managementEmail || !$operationsEmail) {
                \Log::error('Invalid management or operations email addresses provided:', [
                    'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
                    'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                ]);
                throw new \Exception('One or more email addresses are invalid.');
            }
            // Determine if the email is being sent to the customer
            $isCustomerEmail = in_array($customerEmail, $recipients);

            // Send email using a Blade template
            Mail::send('work_order.emails.status_update', [
                'workOrder' => $workOrder,
                'woVehicle' => $woVehicle,
                'accessLink' => $accessLink,
                'statusLogLink' => $statusLogLink,
                'comments' => $validatedData['comment'],
                'statusTracking' => $statusTracking,
                'userName' => $authUserName,
                'status' => $statusName,
                'datetime' => Carbon::now(),
                'isCustomerEmail' => $isCustomerEmail,  // Pass this flag to the email template
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
        $vehicle = WoVehicles::findOrFail($woVehicleId);
        $workOrder = $vehicle->workOrder; // Assuming WoVehicle belongs to WorkOrder
        $type = $workOrder->type;

        // Fetch the previous and next work orders by type
        $previous = WorkOrder::where('type', $type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type', $type)->where('id', '>', $workOrder->id)->min('id');
        $locations = MasterOfficeLocation::select('id','name')->get();

        // Return the view with the status history and navigation data
        return view('work_order.export_exw.wo-vehicle-status-history', compact('data', 'type', 'woVehicleId', 'previous', 'next', 'workOrder', 'vehicle','locations'));
    }
}
