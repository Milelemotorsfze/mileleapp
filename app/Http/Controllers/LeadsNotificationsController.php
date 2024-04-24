<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadsNotifications;
use App\Models\UserActivities;
use App\Models\Calls;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Prospecting;
use Illuminate\Support\Facades\DB;


class LeadsNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $userActivity = new UserActivities();
    $userActivity->activity = "Open Leads Notifications";
    $userActivity->users_id = auth()->id();
    $userActivity->save();
    $userId = auth()->id();
    $leadsNotifications = LeadsNotifications::where('user_id', $userId)
        ->where('status', 'New')
        ->orderBy('id', 'DESC')
        ->get();
    $seenNotifications = LeadsNotifications::where('user_id', $userId)
        ->where('status', 'Seen')
        ->orderBy('id', 'DESC')
        ->take(200)
        ->get();
    return view('dailyleads.notifications', compact('leadsNotifications', 'seenNotifications'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function viewLead($call_id, Request $request)
    {
        $additionalValue = $request->query('additional_param');
        if($additionalValue == "Pending Lead")
        {
            $calls = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
            ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->where('calls.status', 'New')
            ->where('calls.id', $call_id)
            ->first();
        }
        else if($additionalValue == "Fellow Up")
        {
            $calls = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
            ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->join('fellow_up', 'calls.id', '=', 'fellow_up.calls_id')
            ->where('calls.id', $call_id)
            ->first();
        }
        else if($additionalValue == "Quotation Fellow Up")
        {
            $calls = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
            ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->join('quotations', 'calls.id', '=', 'quotations.calls_id')
            ->where('calls.id', $call_id)
            ->first();
        }
        else
        {
            $calls = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
            ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->join('prospectings', 'calls.id', '=', 'prospectings.calls_id')
            ->where('calls.id', $call_id)
            ->first();
        }
        return view('dailyleads.notificationsviews', compact('calls', 'additionalValue', 'call_id'));
    }
    public function updateStatus(Request $request)
{
    // Retrieve the authenticated user's ID
    $userId = auth()->id();
    // Update status of all "New" notifications to "Seen"
    LeadsNotifications::where('user_id', $userId)
        ->where('status', 'New')
        ->update(['status' => 'Seen']);
    // Redirect back to the previous page or any desired page
    return redirect()->back()->with('status', 'All notifications marked as seen.');
}
}
