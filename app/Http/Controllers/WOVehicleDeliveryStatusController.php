<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use App\Models\WOVehicleDeliveryStatus;
use App\Models\WorkOrder;
use App\Models\WOVehicles;
use Illuminate\Validation\Rule;

class WOVehicleDeliveryStatusController extends Controller
{
    public function updateVehDeliveryStatus(Request $request)
    {
        $validatedData = $request->validate([
            'woVehicleId' => 'required|exists:w_o_vehicles,id',
            'status' => 'required|in:On Hold,Ready,Delivered,Delivered With Docs Hold',
            'comment' => 'nullable|string',
            'doc_delivery_date' => 'nullable|date',
            'delivery_at' => 'nullable|date',
            'gdn_number' => 'nullable|string',
            'location' => 'nullable|exists:master_office_locations,id', // Ensure it references a valid location
        ]);

        // Always create a new status_tracking record
        $statusTracking = WOVehicleDeliveryStatus::create([
            'w_o_vehicle_id' => $validatedData['woVehicleId'], // Associate with the work order vehicle ID
            'user_id' => Auth::id(), // Set the ID of the authenticated user
            'status' => $validatedData['status'], // Set the status
            'comment' => $validatedData['comment'], // Optional comment
            'doc_delivery_date' => $validatedData['doc_delivery_date'] ?? null, // Optional, set to null if not provided
            'delivery_at' => $validatedData['delivery_at'] ?? null, // Optional, set to null if not provided
            'gdn_number' => $validatedData['gdn_number'] ?? null, // Optional, set to null if not provided
            'location' => $validatedData['location'] ?? null, // Optional, set to null if not provided
        ]);

        // Fetch the work order vehicle
        $woVehicle = WOVehicles::findOrFail($validatedData['woVehicleId']);
        $workOrder = $woVehicle->workOrder; // Assuming a relationship exists from `WoVehicle` to `WorkOrder`

        // Only send an email if the status is "Completed"
        // if ($validatedData['status'] === 'Completed') {
        //     // Prepare email template details
        //     $template = [
        //         'from' => 'no-reply@milele.com',
        //         'from_name' => 'Milele Matrix',
        //     ];

        //     // Handle cases where customer_name is null
        //     $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        //     $statusName = $validatedData['status'];

        //     // Prepare email subject
        //     $subject = "Work Order Vehicle delivery Status changed to $statusName for Vehicle VIN: " . $woVehicle->vin . " under Work Order " . $workOrder->wo_number;

        //     // Retrieve the authenticated user's name
        //     $authUserName = auth()->user()->name;

        //     // Define a quick access link (adjust the route as needed)
        //     $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        //     $statusLogLink = env('BASE_URL') . '/vehicle-delivery-status-log/' . $woVehicle->id;

        //     // Retrieve and validate email addresses from .env
        //     $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        //     $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        //     $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
        //     $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);

        //     // Log email addresses to help with debugging
        //     \Log::info('Email Recipients:', [
        //         'managementEmail' => $managementEmail,
        //         'operationsEmail' => $operationsEmail,
        //         'createdByEmail' => $createdByEmail,
        //         'salesPersonEmail' => $salesPersonEmail,
        //     ]);

        //     // Check if the salesPerson exists before trying to access properties
        //     $shouldSendToSalesPerson = false;
        //     if ($workOrder->salesPerson) {
        //         $shouldSendToSalesPerson = $createdByEmail !== $salesPersonEmail
        //             && $workOrder->salesPerson->is_sales_rep === 'Yes'
        //             && $workOrder->salesPerson->is_management === 'No';
        //     }

        //     // Initialize recipient list
        //     $recipients = [$managementEmail, $operationsEmail, $createdByEmail];

        //     // Add salesPersonEmail only if the condition is met
        //     if ($shouldSendToSalesPerson && $salesPersonEmail) {
        //         $recipients[] = $salesPersonEmail;
        //     }

        //     // Log and handle invalid email addresses
        //     if (!$managementEmail || !$operationsEmail || !$createdByEmail) {
        //         \Log::error('Invalid email addresses provided:', [
        //             'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
        //             'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
        //             'createdByEmail' => $createdByEmail,
        //         ]);
        //         throw new \Exception('One or more email addresses are invalid.');
        //     }

        //     // Send email using a Blade template
        //     Mail::send('work_order.emails.delivery_status_update', [
        //         'workOrder' => $workOrder,
        //         'woVehicle' => $woVehicle,
        //         'accessLink' => $accessLink,
        //         'statusLogLink' => $statusLogLink,
        //         'comments' => $validatedData['comment'],
        //         'userName' => $authUserName,
        //         'status' => $statusName,
        //         'datetime' => Carbon::now(),
        //         'statusTracking' => $statusTracking,
        //     ], function ($message) use ($subject, $recipients, $template) {
        //         $message->from($template['from'], $template['from_name'])
        //                 ->to($recipients)
        //                 ->subject($subject);
        //     });
        // }

        // Return a JSON response indicating success
        return response()->json(['message' => 'Status updated successfully', 'data' => $statusTracking]);
    }

    public function vehDeliveryStatusHistory($woVehicleId)
    {
        // Fetch the status history for the specific vehicle from status_tracking
        $data = WOVehicleDeliveryStatus::where('w_o_vehicle_id', $woVehicleId)->orderBy('created_at', 'DESC')->get();

        // Fetch the associated work order vehicle and work order
        $vehicle = WOVehicles::findOrFail($woVehicleId);
        $workOrder = $vehicle->workOrder; // Assuming WoVehicle belongs to WorkOrder
        $type = $workOrder->type;

        // Fetch the previous and next work orders by type
        $previous = WorkOrder::where('type', $type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type', $type)->where('id', '>', $workOrder->id)->min('id');

        // Return the view with the status history and navigation data
        return view('work_order.export_exw.delivery-status-history', compact('data', 'type', 'woVehicleId', 'previous', 'next', 'workOrder', 'vehicle'));
    }
}
