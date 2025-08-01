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
        // Validate the incoming request data
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

        // Retrieve email addresses from the users table where can_send_wo_email is true
        $managementEmails = \App\Models\User::where('can_send_wo_email', true)->pluck('email')->filter(function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        })->toArray();
        // Retrieve and validate other recipients' emails
        $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL);
        $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
        $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);
        // Fetch `DONT_SEND_EMAIL` and `REDIRECT_SALES_EMAIL_TO` from .env
        $dontSendEmail = env('DONT_SEND_EMAIL');
        $redirectSalesEmailTo = filter_var(env('REDIRECT_SALES_EMAIL_TO'), FILTER_VALIDATE_EMAIL);
        // $customerEmail = filter_var($workOrder->customer_email ?? null, FILTER_VALIDATE_EMAIL);
        $customerEmail = '';
        // Log email recipients for debugging
        \Log::info('Email Recipients:', [
            'managementEmails' => $managementEmails,
            'operationsEmail' => $operationsEmail,
            'createdByEmail' => $createdByEmail,
            'salesPersonEmail' => $salesPersonEmail,
            'customerEmail' => $customerEmail,
        ]);
        // Initialize recipient list with management emails from the database and operations email
        $recipients = array_filter(array_merge($managementEmails, [$operationsEmail]));
        // Add createdByEmail if valid
        if ($createdByEmail) {
            $recipients[] = $createdByEmail;
        }
        // Handle salesPersonEmail conditions
        if ($workOrder->salesPerson && $salesPersonEmail && $createdByEmail !== $salesPersonEmail) {
            if ($salesPersonEmail !== $dontSendEmail) {
                // If salesperson's email is not blocked, add it
                $recipients[] = $salesPersonEmail;
            } elseif ($salesPersonEmail === $dontSendEmail && $redirectSalesEmailTo) {
                // Redirect email if salesPersonEmail matches DONT_SEND_EMAIL
                $recipients[] = $redirectSalesEmailTo;
            }
        }

        // Add customer email for "Ready" status
        if ($validatedData['status'] === 'Ready' && $customerEmail) {
            $recipients[] = $customerEmail;
        }

        // If no valid recipients, skip sending the email and log the issue
        if (empty($recipients)) {
            \Log::info('No valid recipients found. Skipping email sending for WO-' . $workOrder->wo_number);
            return response()->json(['message' => 'Status updated successfully', 'data' => $statusTracking]);
        }

        // Prepare the email subject based on status
        $statusName = $validatedData['status'];
        $subject = "Work Order Vehicle Delivery Status changed to $statusName for Vehicle VIN: " . $woVehicle->vin . " under Work Order " . $workOrder->wo_number;

        // Retrieve the authenticated user's name
        $authUserName = auth()->user()->name;

        // Define quick access links
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $statusLogLink = env('BASE_URL') . '/vehicle-delivery-status-log/' . $woVehicle->id;

        try {
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
                'isCustomerEmail' => $validatedData['status'] === 'Ready', // To control the view for customer email
            ], function ($message) use ($subject, $recipients) {
                $message->from('no-reply@milele.com', 'Milele Matrix')
                        ->to($recipients)
                        ->subject($subject);
            });
        } catch (\Exception $e) {
            \Log::error($e);
        }

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
