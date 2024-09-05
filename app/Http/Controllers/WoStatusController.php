<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Auth;
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
