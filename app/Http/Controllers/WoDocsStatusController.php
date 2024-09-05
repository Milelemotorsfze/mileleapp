<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Auth;
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
