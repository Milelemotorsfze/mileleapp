<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadsNotifications;
use App\Models\UserActivities;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Calls;
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
    public function viewLead($call_id)
    {
        // Retrieve the lead using the call_id
        $lead = Calls::where('id', $call_id)->first();
        if (!$lead) {
            // Lead not found, you can redirect or show an error message
            return redirect()->route('leads.index')->with('error', 'Lead not found');
        }

        // Assuming you have a view to display lead details
        return view('leads.show', ['lead' => $lead]);
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
