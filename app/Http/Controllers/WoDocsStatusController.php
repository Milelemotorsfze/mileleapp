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
use App\Models\WOBOE;

class WoDocsStatusController extends Controller
{
    public function updateDocStatus(Request $request)
    {   
        // Normalize the 'hasClaim' input to a valid boolean value
        $request->merge([
            'hasClaim' => filter_var($request->input('hasClaim'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0
        ]);
        // Validate the incoming request data
        $validatedData = $request->validate([
            'workOrderId' => 'required|exists:work_orders,id',
            'status' => 'required|in:Not Initiated,In Progress,Ready',
            'comment' => 'nullable|string',
            'hasClaim' => 'nullable|in:0,1', // Only accept 0 or 1
            'boeData' => 'nullable|array', // BOE data is optional
            'boeData.*.boe_number' => 'string|nullable', // BOE number should be a string
            'boeData.*.boe' => 'string|nullable', // BOE field should be a string
            'boeData.*.declaration_number' => 'nullable|digits:13', // 13 digits, optional
            'boeData.*.declaration_date' => 'nullable|date', // Valid date, optional
        ]);

        // Always create a new wo_docs_status record
        $woDocStatus = WoDocsStatus::create([
            'wo_id' => $validatedData['workOrderId'], // Associate with work order ID
            'is_docs_ready' => $validatedData['status'], // Set the document status
            'documentation_comment' => $validatedData['comment'], // Optional comment
            'doc_status_changed_by' => Auth::id(), // Set the ID of the authenticated user
            'doc_status_changed_at' => now(), // Set the current timestamp
        ]);
        // Handle the BOE data if it exists
        if (!empty($validatedData['boeData'])) {
            foreach ($validatedData['boeData'] as $boeEntry) {
                // Check if the record already exists
                $existingBoe = WOBOE::where('wo_id', $validatedData['workOrderId'])
                                    ->where('boe_number', $boeEntry['boe_number'] ?? null) // Safely access boe_number
                                    ->first();

                if ($existingBoe) {
                    // Update existing BOE record
                    $existingBoe->update([
                        'boe' => $boeEntry['boe'] ?? null, // Safely access 'boe' with null fallback
                        'declaration_number' => $boeEntry['declaration_number'] ?? null,
                        'declaration_date' => $boeEntry['declaration_date'] ?? null,
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    // Create a new BOE record
                    WOBOE::create([
                        'wo_id' => $validatedData['workOrderId'],
                        'boe_number' => $boeEntry['boe_number'] ?? null, // Safely access 'boe_number'
                        'boe' => $boeEntry['boe'] ?? null, // Safely access 'boe'
                        'declaration_number' => $boeEntry['declaration_number'] ?? null,
                        'declaration_date' => $boeEntry['declaration_date'] ?? null,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
        }
        // Determine the has_claim value for WorkOrder
        $hasClaimValue = ($validatedData['status'] === 'Ready') 
            ? ($validatedData['hasClaim'] ? 'yes' : 'no') 
            : null;
        // Fetch the work order vehicle
        $workOrder = WorkOrder::findOrFail($validatedData['workOrderId']);
        // Update the WorkOrders table with has_claim and status
        $workOrder->update([
            'has_claim' => $hasClaimValue,
        ]);

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
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
            $salesPersonEmail = filter_var(optional($workOrder->salesPerson)->email, FILTER_VALIDATE_EMAIL);
            // $customerEmail = filter_var($workOrder->customer_email, FILTER_VALIDATE_EMAIL);
            $customerEmail = '';
            // Fetch `DONT_SEND_EMAIL` and `REDIRECT_SALES_EMAIL_TO` from .env
            $dontSendEmail = env('DONT_SEND_EMAIL');
            $redirectSalesEmailTo = filter_var(env('REDIRECT_SALES_EMAIL_TO'), FILTER_VALIDATE_EMAIL);
            // Get all users with 'can_send_wo_email' set to 'yes'
            $managementEmails = \App\Models\User::where('can_send_wo_email', 'yes')->pluck('email')->filter(function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })->toArray();

            // Log email addresses to help with debugging
            \Log::info('Email Recipients:', [
                'operationsEmail' => $operationsEmail,
                'createdByEmail' => $createdByEmail,
                'salesPersonEmail' => $salesPersonEmail,
                'customerEmail' => $customerEmail,
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

            // Add customerEmail if valid
            if ($customerEmail) {
                $recipients[] = $customerEmail;
            }

            // Log and handle invalid email addresses for operations and createdByEmail (skip email if missing)
            if (!$operationsEmail || !$createdByEmail) {
                \Log::error('Invalid or missing email addresses:', [
                    'operationsEmail' => env('OPERATIONS_TEAM_EMAIL'),
                    'createdByEmail' => $createdByEmail,
                ]);
            }
            // If no valid recipients, skip sending the email but don't stop execution
            if (empty($recipients)) {
                \Log::info('No valid recipients found. Skipping email sending for Work Order: ' . $workOrder->wo_number);
                return;
            }
            // Determine if the email is being sent to the customer
            $isCustomerEmail = in_array($customerEmail, $recipients);

            // Send email using a Blade template
            // Mail::send('work_order.emails.docs_status_update', [
            //     'workOrder' => $workOrder,
            //     'accessLink' => $accessLink,
            //     'statusLogLink' => $statusLogLink,
            //     'comments' => $validatedData['comment'],
            //     'userName' => $authUserName,
            //     'status' => $statusName,
            //     'datetime' => Carbon::now(),
            //     'isCustomerEmail' => $isCustomerEmail,  // Pass this flag to the email template
            //     'declarationNumber' => $validatedData['declarationNumber'] ?? 'N/A', // Pass Declaration Number
            //     'declarationDate' => $validatedData['declarationDate'] ?? 'N/A', // Pass Declaration Date
            // ], function ($message) use ($subject, $recipients, $template) {
            //     $message->from($template['from'], $template['from_name'])
            //             ->to($recipients)
            //             ->subject($subject);
            // });
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
