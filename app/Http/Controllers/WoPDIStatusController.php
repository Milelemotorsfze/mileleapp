<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use App\Models\WOVehiclePDIStatus;
use App\Models\WorkOrder;
use App\Models\WOVehicles;
use Illuminate\Validation\Rule;

class WoPDIStatusController extends Controller
{
    public function updateVehPdiStatus(Request $request)
    {
        $validatedData = $request->validate([
            'woVehicleId' => 'required|exists:w_o_vehicles,id',
            'status' => 'required|in:Not Initiated,Scheduled,Completed',
            'comment' => 'nullable|string',
            'pdi_scheduled_at' => 'nullable|date',
            'qc_inspector_remarks' => 'nullable|string',
            // Apply validation to `passed_status` only if status is 'Completed'
            'passed_status' => [
                Rule::requiredIf($request->status === 'Completed'),
                'nullable',
                Rule::in(['Passed', 'Failed']),
            ],
        ]);

        // Always create a new status_tracking record
        $statusTracking = WOVehiclePDIStatus::create([
            'w_o_vehicle_id' => $validatedData['woVehicleId'], // Associate with the work order vehicle ID
            'user_id' => Auth::id(), // Set the ID of the authenticated user
            'status' => $validatedData['status'], // Set the status
            'comment' => $validatedData['comment'], // Optional comment
            'pdi_scheduled_at' => $validatedData['pdi_scheduled_at'] ?? null, // Optional, set to null if not provided
            'qc_inspector_remarks' => $validatedData['qc_inspector_remarks'] ?? null, // Optional, set to null if not provided
            'passed_status' => $validatedData['passed_status'] ?? null, // Optional, set to null if not provided
        ]);

        // Fetch the work order vehicle
        $woVehicle = WOVehicles::findOrFail($validatedData['woVehicleId']);
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

            // Define quick access links
            $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
            $statusLogLink = env('BASE_URL') . '/vehicle-pdi-status-log/' . $woVehicle->id;

            // Retrieve and validate email addresses from .env
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
            $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);
            // Fetch `DONT_SEND_EMAIL` and `REDIRECT_SALES_EMAIL_TO` from .env
            $dontSendEmail = env('DONT_SEND_EMAIL');
            $redirectSalesEmailTo = filter_var(env('REDIRECT_SALES_EMAIL_TO'), FILTER_VALIDATE_EMAIL);
            // Get all users with 'can_send_wo_email' set to 'yes' from the database
            $managementEmails = \App\Models\User::where('can_send_wo_email', 'yes')->pluck('email')->filter(function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })->toArray();

            // Log email addresses to help with debugging
            \Log::info('Email Recipients:', [
                'operationsEmail' => $operationsEmail,
                'createdByEmail' => $createdByEmail,
                'salesPersonEmail' => $salesPersonEmail,
                'managementEmails' => implode(', ', $managementEmails),
            ]);

            // Check if the salesPerson exists before trying to access properties
            $shouldSendToSalesPerson = false;
            if ($workOrder->salesPerson) {
                $shouldSendToSalesPerson = $createdByEmail !== $salesPersonEmail
                    && $workOrder->salesPerson->is_sales_rep === 'Yes'
                    && $workOrder->salesPerson->is_management === 'No';
            }

            // Initialize recipient list with operations email and management emails from the database
            $recipients = array_filter(array_merge([$operationsEmail, $createdByEmail], $managementEmails));

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

            // Log and handle invalid email addresses (but do not throw an exception, just log)
            if (!$operationsEmail || !$createdByEmail) {
                \Log::error('Invalid or missing email addresses:', [
                    'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                    'createdByEmail' => $createdByEmail,
                ]);
            }
            // If no valid recipients, skip sending the email but don't stop execution
            if (empty($recipients)) {
                \Log::info('No valid recipients found. Skipping email sending for Vehicle VIN: ' . $woVehicle->vin . ' under Work Order: ' . $workOrder->wo_number);
                return;
            }

            // // Send email using a Blade template
            // Mail::send('work_order.emails.pdi_status_update', [
            //     'workOrder' => $workOrder,
            //     'woVehicle' => $woVehicle,
            //     'accessLink' => $accessLink,
            //     'statusLogLink' => $statusLogLink,
            //     'comments' => $validatedData['comment'],
            //     'userName' => $authUserName,
            //     'status' => $statusName,
            //     'datetime' => Carbon::now(),
            //     'statusTracking' => $statusTracking,
            // ], function ($message) use ($subject, $recipients, $template) {
            //     $message->from($template['from'], $template['from_name'])
            //             ->to($recipients)
            //             ->subject($subject);
            // });
        }

        // Return a JSON response indicating success
        return response()->json(['message' => 'Status updated successfully', 'data' => $statusTracking]);
    }

    public function vehPdiStatusHistory($woVehicleId)
    {
        // Fetch the status history for the specific vehicle from status_tracking
        $data = WOVehiclePDIStatus::where('w_o_vehicle_id', $woVehicleId)->orderBy('created_at', 'DESC')->get();

        // Fetch the associated work order vehicle and work order
        $vehicle = WOVehicles::findOrFail($woVehicleId);
        $workOrder = $vehicle->workOrder; // Assuming WoVehicle belongs to WorkOrder
        $type = $workOrder->type;

        // Fetch the previous and next work orders by type
        $previous = WorkOrder::where('type', $type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type', $type)->where('id', '>', $workOrder->id)->min('id');

        // Return the view with the status history and navigation data
        return view('work_order.export_exw.pdi-status-history', compact('data', 'type', 'woVehicleId', 'previous', 'next', 'workOrder', 'vehicle'));
    }
}
