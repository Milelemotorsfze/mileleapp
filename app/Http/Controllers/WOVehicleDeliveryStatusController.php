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
            'delivered_at' => 'nullable|date',
            'location' => 'nullable|exists:master_office_locations,id', // Ensure it references a valid location
        ]);

        // Always create a new status_tracking record
        $statusTracking = WOVehicleDeliveryStatus::create([
            'w_o_vehicle_id' => $validatedData['woVehicleId'],
            'user_id' => Auth::id(),
            'status' => $validatedData['status'],
            'comment' => $validatedData['comment'],
            'doc_delivery_date' => $validatedData['doc_delivery_date'] ?? null,
            'delivery_at' => $validatedData['delivery_at'] ?? null,
            'gdn_number' => $validatedData['gdn_number'] ?? null,
            'delivered_at' => $validatedData['delivered_at'] ?? null,
            'location' => $validatedData['location'] ?? null,
        ]);

        // Fetch the work order vehicle and related data
        $woVehicle = WOVehicles::findOrFail($validatedData['woVehicleId']);
        $workOrder = $woVehicle->workOrder;

        // Define recipient emails based on status
        $recipients = [];
        $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
        $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);
        $customerEmail = filter_var($workOrder->customer_email ?? null, FILTER_VALIDATE_EMAIL); // Assume work order has customer email

        // Basic recipient list (management, operations, and created by user)
        $recipients = [$managementEmail, $operationsEmail, $createdByEmail];

        // Add sales person to the recipient list if the condition is met
        if ($workOrder->salesPerson && $salesPersonEmail && $createdByEmail !== $salesPersonEmail) {
            $recipients[] = $salesPersonEmail;
        }

        // Add customer email for "Ready" status
        if ($validatedData['status'] === 'Ready' && $customerEmail) {
            $recipients[] = $customerEmail;
        }

        // Log email recipients for debugging
        \Log::info('Email Recipients:', [
            'managementEmail' => $managementEmail,
            'operationsEmail' => $operationsEmail,
            'createdByEmail' => $createdByEmail,
            'salesPersonEmail' => $salesPersonEmail,
            'customerEmail' => $customerEmail,
        ]);

        // Handle invalid email addresses
        if (!$managementEmail || !$operationsEmail || !$createdByEmail) {
            \Log::error('Invalid email addresses provided:', [
                'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
                'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                'createdByEmail' => $createdByEmail,
            ]);
            throw new \Exception('One or more email addresses are invalid.');
        }

        // Prepare the email subject based on status
        $statusName = $validatedData['status'];
        $subject = "Work Order Vehicle Delivery Status changed to $statusName for Vehicle VIN: " . $woVehicle->vin . " under Work Order " . $workOrder->wo_number;

        // Retrieve the authenticated user's name
        $authUserName = auth()->user()->name;

        // Define quick access links
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $statusLogLink = env('BASE_URL') . '/vehicle-delivery-status-log/' . $woVehicle->id;

        // Send email using Blade template
        Mail::send('work_order.emails.delivery_status_update', [
            'workOrder' => $workOrder,
            'woVehicle' => $woVehicle,
            'accessLink' => $accessLink,
            'statusLogLink' => $statusLogLink,
            'comments' => $validatedData['comment'],
            'userName' => $authUserName,
            'status' => $statusName,
            'datetime' => Carbon::now(),
            'statusTracking' => $statusTracking,
            'isCustomerEmail' => $validatedData['status'] === 'Ready' ? true : false, // To control the view for customer email
        ], function ($message) use ($subject, $recipients) {
            $message->from('no-reply@milele.com', 'Milele Matrix')
                    ->to($recipients)
                    ->subject($subject);
        });

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
