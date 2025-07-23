<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use App\Models\WoStatus;
use App\Models\WorkOrder;

class WoStatusController extends Controller
{
    public function updateStatus(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'workOrderId' => 'required|exists:work_orders,id',
            'status' => 'required|in:Active,On Hold,Partially Delivered,Succeeded,Cancelled', // Add new statuses here
            'comment' => 'nullable|string',
        ]);

        // Always create a new wo_docs_status record
        $woDocStatus = WoStatus::create([
            'wo_id' => $validatedData['workOrderId'], // Associate with work order ID
            'status' => $validatedData['status'], // Set the document status
            'comment' => $validatedData['comment'], // Optional comment
            'status_changed_by' => Auth::id(), // Set the ID of the authenticated user
            'status_changed_at' => now(), // Set the current timestamp
        ]);
        // // Prepare email template details
        // $template = [
        //     'from' => 'no-reply@milele.com',
        //     'from_name' => 'Milele Matrix',
        // ];

        // // Fetch the work order
        // $workOrder = WorkOrder::findOrFail($validatedData['workOrderId']);

        // // Handle cases where customer_name is null
        // $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        // $statusName = $validatedData['status'];

        // // Prepare email subject
        // $subject = "Work Order Status changed to $statusName for " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;

        // // Retrieve the authenticated user's name
        // $authUserName = auth()->user()->name;

        // // Define a quick access link (adjust the route as needed)
        // $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        // $statusLogLink = env('BASE_URL') . '/wo-status-history/' . $workOrder->id;

        // // Retrieve email addresses from the users table where can_send_wo_email is true
        // $managementEmails = \App\Models\User::where('can_send_wo_email', true)->pluck('email')->filter(function($email) {
        //     return filter_var($email, FILTER_VALIDATE_EMAIL);
        // })->toArray();
        // // Log and handle if no management emails are found
        // if (empty($managementEmails)) {
        //     \Log::info('No valid email addresses found for users with permission to receive WO emails. Skipping email for WO-' . $workOrder->wo_number);
        //     // No need to throw an exception, just continue without sending the email
        // }
        // // Retrieve and validate email addresses from .env for operations team
        // $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL);

        // // Log and handle invalid operations email
        // if (!$operationsEmail) {
        //     \Log::info('Invalid or missing operations team email. Skipping email for WO-' . $workOrder->wo_number);
        // }
        // // Combine all valid recipient emails
        // $recipients = array_filter(array_merge($managementEmails, [$operationsEmail]));
        // // If no valid recipients, skip sending the email
        // if (empty($recipients)) {
        //     \Log::info('No valid recipients found. Skipping email sending for WO-' . $workOrder->wo_number);
        //     return; // Exit the function without throwing an exception
        // }

        // // Send email using a Blade template
        // Mail::send('work_order.emails.status_update', [
        //     'workOrder' => $workOrder,
        //     'accessLink' => $accessLink,
        //     'statusLogLink' => $statusLogLink,
        //     'comments' => $validatedData['comment'],
        //     'userName' => $authUserName,
        //     'status' => $statusName, // Use the correct status name
        //     'datetime' => Carbon::now(),
        // ], function ($message) use ($subject, $recipients, $template) {
        //     $message->from($template['from'], $template['from_name'])
        //             ->to($recipients)
        //             ->subject($subject);
        // });
        // Return a JSON response indicating success
        return response()->json(['message' => 'Status updated successfully', 'data' => $woDocStatus]);
    }
    public function woStatusHistory($id)
    {
        $data = WoStatus::where('wo_id', $id)->orderBy('status_changed_at','DESC')->get();
        $workOrder = WorkOrder::where('id',$id)->first();
        $type = $workOrder->type;
        $previous = WorkOrder::where('type',$type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type',$type)->where('id', '>', $workOrder->id)->min('id');
        return view('work_order.export_exw.status-history', compact('data','type','id','previous','next','workOrder'));
    }
}
