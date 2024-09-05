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
            'status' => 'required|in:Active,On Hold',
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
        // Prepare email template details
        $template = [
            'from' => 'no-reply@milele.com',
            'from_name' => 'Milele Matrix',
        ];

        // Fetch the work order
        $workOrder = WorkOrder::findOrFail($validatedData['workOrderId']);  

        // Handle cases where customer_name is null
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        $statusName = $validatedData['status'];

        // Prepare email subject
        $subject = "Work Order Status changed to $statusName for " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;

        // Retrieve the authenticated user's name
        $authUserName = auth()->user()->name;

        // Define a quick access link (adjust the route as needed)
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $statusLogLink = env('BASE_URL') . '/wo-status-history/' . $workOrder->id;

        // Retrieve and validate email addresses from .env
        $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';

        // Log and handle invalid email addresses
        if (!$managementEmail || !$operationsEmail) {
            \Log::error('Invalid email addresses provided:', [
                'managementEmail' => env('MANAGEMENT_TEAM_EMAIL'),
                'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
            ]);
            throw new \Exception('One or more email addresses are invalid.');
        }

        // Send email using a Blade template
        Mail::send('work_order.emails.status_update', [
            'workOrder' => $workOrder,
            'accessLink' => $accessLink,
            'statusLogLink' => $statusLogLink,
            'comments' => $validatedData['comment'],
            'userName' => $authUserName,
            'status' => $statusName, // Use the correct status name
            'datetime' => Carbon::now(),
        ], function ($message) use ($subject, $managementEmail, $operationsEmail, $template) {
            $message->from($template['from'], $template['from_name'])
                    ->to([$managementEmail, $operationsEmail])
                    ->subject($subject);
        });
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
