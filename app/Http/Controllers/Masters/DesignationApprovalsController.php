<?php

namespace App\Http\Controllers\Masters;

use App\Http\Controllers\Controller;
use App\Models\HRM\Approvals\ApprovalByPositions;
use Illuminate\Http\Request;

class DesignationApprovalsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ApprovalByPositions::all();
        return view('hrm.masters.designationApprovals.index',compact('data'));
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
    public function show(ApprovalByPositions $approvalByPositions)
    {
        $errorMsg ="Comong Soon ! This page is under testing now.. You can access later !";
        return view('hrm.notaccess',compact('errorMsg'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApprovalByPositions $approvalByPositions)
    {
        $errorMsg ="Comong Soon ! This page is under testing now.. You can access later !";
        return view('hrm.notaccess',compact('errorMsg'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApprovalByPositions $approvalByPositions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApprovalByPositions $approvalByPositions)
    {
        //
    }
}
