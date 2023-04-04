<?php

namespace App\Http\Controllers;

use App\Models\Dailyleads;
use App\Models\Calls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyleadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $name = Auth::user()->name;
       $pendingdata = Calls::where('status', 'New')->where('sales_person', $name)->get();
       $datainstock = Dailyleads::where('status', 'Already In Stock')->orwhere('status', 'Request To Management Rejected')->where('sales_person', $name)->get();
       $datainmanagement = Dailyleads::where('status', 'Request To Management')->where('sales_person', $name)->get();
       $dataremarks = Dailyleads::where('status', 'Request To Management Remarks')->where('sales_person', $name)->get();
       $dataapproved = Dailyleads::where('status', 'Request To Management Approved')->where('sales_person', $name)->get();
       return view('dailyleads.index',compact('pendingdata', 'datainstock', 'datainmanagement', 'dataremarks', 'dataapproved'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dailyleads.create');
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
    public function show(Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dailyleads $dailyleads)
    {
        //
    }
}
