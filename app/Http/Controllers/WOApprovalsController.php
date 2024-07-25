<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WOApprovals;
use Illuminate\Http\Request;

class WOApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WOApprovals $wOApprovals)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WOApprovals $wOApprovals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WOApprovals $wOApprovals)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WOApprovals $wOApprovals)
    {
        //
    }
    public function fetchFinanceApprovalHistory($id)
    {
        $data = WOApprovals::where('work_order_id', $id)->where('type', 'finance')->orderBy('id','DESC')->get();
        $workOrder = WorkOrder::where('id',$id)->first();
        $type = $workOrder->type;
        $previous = WorkOrder::where('type',$type)->where('id', '<', $workOrder->id)->max('id');
        $next = WorkOrder::where('type',$type)->where('id', '>', $workOrder->id)->min('id');
        return view('work_order.export_exw.finance-approval-history-page', compact('data','type','id','previous','next'));
    }

    public function showFinanceApprovalHistoryPage($id)
    {
        // Retrieve data from the session
        $data = session('financeApprovalHistory');

        // Pass data to the Blade view
        return view('work_order.export_exw.finance-approval-history-page', compact('data'));
    }

    public function fetchCooApprovalHistory($id)
    {
        // Fetch data from the database
        $data = YourModel::where('approval_type', 'coo')->get();

        // Store data in session or pass it to the view
        session(['cooApprovalHistory' => $data]);

        // Return a response indicating success
        return response()->json(['success' => true]);
    }

    public function showCooApprovalHistoryPage($id)
    {
        // Retrieve data from the session
        $data = session('cooApprovalHistory');

        // Pass data to the Blade view
        return view('coo-approval-history-page', compact('data'));
    }
}
