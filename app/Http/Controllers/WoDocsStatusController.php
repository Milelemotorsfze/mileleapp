<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Exception;
use App\Models\WoDocsStatus;
use App\Models\WorkOrder;

class WoDocsStatusController extends Controller
{
    public function updateDocStatus(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'workOrderId' => 'required|exists:work_orders,id',
            'status' => 'required|in:Not Initiated,In Progress,Ready',
            'comment' => 'nullable|string',
        ]);

        // Always create a new wo_docs_status record
        $woDocStatus = WoDocsStatus::create([
            'wo_id' => $validatedData['workOrderId'], // Associate with work order ID
            'is_docs_ready' => $validatedData['status'], // Set the document status
            'documentation_comment' => $validatedData['comment'], // Optional comment
            'doc_status_changed_by' => Auth::id(), // Set the ID of the authenticated user
            'doc_status_changed_at' => now(), // Set the current timestamp
        ]);
        // Fetch the work order vehicle
        $workOrder = WorkOrder::findOrFail($validatedData['workOrderId']);

        // Only send an email if the status is "Ready"
        if ($validatedData['status'] === 'Ready') {
            // Prepare email template details
            $template = [
                'from' => 'no-reply@milele.com',
                'from_name' => 'Milele Matrix',
            ];

            // Handle cases where customer_name is null
            $customerName = $workOrder->customer_name ?? 'Unknown Customer';
            $statusName = $validatedData['status'];

            // Prepare email subject
            $subject = "Work Order Documentation Status changed to $statusName for Work Order " . $workOrder->wo_number;

            // Retrieve the authenticated user's name
            $authUserName = auth()->user()->name;

            // Define a quick access link (adjust the route as needed)
            $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
            $statusLogLink = env('BASE_URL') . '/wo-doc-status-history/' . $workOrder->id;

            // Retrieve and validate email addresses from .env
            $managementEmail = filter_var(env('MANAGEMENT_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
            $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);
            $customerEmail = filter_var($workOrder->customer_email, FILTER_VALIDATE_EMAIL);

            // Log email addresses to help with debugging
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
            $recipients = [$managementEmail, $operationsEmail, $createdByEmail];

            // Add salesPersonEmail only if the condition is met
            if ($shouldSendToSalesPerson && $salesPersonEmail) {
                $recipients[] = $salesPersonEmail;
            }

            // Add customerEmail if valid
            if ($customerEmail) {
                $recipients[] = $customerEmail;
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
            // Determine if the email is being sent to the customer
            $isCustomerEmail = in_array($customerEmail, $recipients);

            // Send email using a Blade template
            Mail::send('work_order.emails.docs_status_update', [
                'workOrder' => $workOrder,
                'accessLink' => $accessLink,
                'statusLogLink' => $statusLogLink,
                'comments' => $validatedData['comment'],
                'userName' => $authUserName,
                'status' => $statusName,
                'datetime' => Carbon::now(),
                'isCustomerEmail' => $isCustomerEmail,  // Pass this flag to the email template
            ], function ($message) use ($subject, $recipients, $template) {
                $message->from($template['from'], $template['from_name'])
                        ->to($recipients)
                        ->subject($subject);
            });
        }

        // Return a JSON response indicating success
        return response()->json(['message' => 'Status updated successfully', 'data' => $woDocStatus]);
    }
    public function docStatusHistory($id)
    {
        $data = WoDocsStatus::where('wo_id', $id)->orderBy('doc_status_changed_at','DESC')->get();
        $workOrder = WorkOrder::where('id',$id)->first();
        $type = $workOrder->type;
        $previous = WorkOrder::where('type',$type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type',$type)->where('id', '>', $workOrder->id)->min('id');
        return view('work_order.export_exw.doc-status-history', compact('data','type','id','previous','next','workOrder'));
    }
}
