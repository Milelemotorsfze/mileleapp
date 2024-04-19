<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadsNotifications;
use App\Models\UserActivities;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;


class LeadsNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "Open Leads Notifications";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
                $data = LeadsNotifications::select([
                    'leads_notifications.id',
                    'leads_notifications.remarks',
                    'leads_notifications.status',
                    'leads_notifications.calls_id',
                ])
                ->leftJoin('users', 'leads_notifications.user_id', '=', 'users.id')
                ->leftJoin('calls', 'leads_notifications.calls_id', '=', 'calls.id')
                ->where('status', 'new');
                $data = $data->groupBy('leads_notifications.id');
            if ($data) {
                return DataTables::of($data)->toJson();
            }
        }
        return view('dailyleads.notifications');
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
}
