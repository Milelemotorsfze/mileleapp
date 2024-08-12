<?php

namespace App\Http\Controllers;
use App\Models\Dnaccess;
use App\Models\DepartmentNotifications;
use Illuminate\Http\Request;

class DepartmentNotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $userDepartmentId = auth()->user()->empProfile->department_id;

    // Fetch the notifications for this department with pagination
    $notifications = DepartmentNotifications::whereHas('departments', function($query) use ($userDepartmentId) {
        $query->where('master_departments_id', $userDepartmentId);
    })->whereDoesntHave('viewedLogs', function($query) {
        $query->where('users_id', auth()->id());
    })->paginate(3); // Adjust the number of notifications per page as needed
    
    // Return view with notifications
    return view('notifications.index', compact('notifications'));
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
