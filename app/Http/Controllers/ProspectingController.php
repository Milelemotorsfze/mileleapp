<?php

namespace App\Http\Controllers;

use App\Models\Prospecting;
use App\Models\Fellowup;
use App\Models\Calls;
use Illuminate\Http\RedirectResponse; // Import the RedirectResponse class
use Illuminate\Support\Facades\Redirect; // You might need to import this if not already imported
use Illuminate\Http\Request;

class ProspectingController extends Controller
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
        $prospectingmedium = $request->input('prospectingmedium');
        $call_id = $request->input('call_id');
        $prospectingtime = $request->input('prospectingtime');
        $prospectingdate = $request->input('prospectingdate');
        $dealvalue = $request->input('dealvalue');
        $salesnotes = $request->input('salesnotes');
        $alternativephone = $request->input('alternativephone');
        $alternativemail = $request->input('alternativemail');
        $modification = $request->input('modification');
        $prospecting = New Prospecting();
        $prospecting->medium = $prospectingmedium;
        $prospecting->time = $prospectingtime;
        $prospecting->date = $prospectingdate;
        $prospecting->dealvalue = $dealvalue;
        $prospecting->salesnotes = $salesnotes;
        $prospecting->alternativephone = $alternativephone;
        $prospecting->alternativeemail = $alternativemail;
        $prospecting->modification = $modification;
        $prospecting->calls_id = $call_id;
        $prospecting->save();
        $followUpOption = $request->input('follow-up', 'not-required');
        if ($followUpOption === 'set-follow-up') {
        $fellowupmedium = $request->input('fellowupmedium');
        $fellowuptime = $request->input('fellowuptime');
        $fellowupdate = $request->input('fellowupdate');
        $fellowup = New Fellowup();
        $fellowup->date = $fellowupdate;
        $fellowup->time = $fellowuptime;
        $fellowup->medium = $fellowupmedium;
        $fellowup->calls_id = $call_id;
        $fellowup->save();
        }
        $call = Calls::findOrFail($call_id);
        $call->status = "Prospecting";
        $call->save();
        return redirect()->route('dailyleads.index')->with('success', 'Call status updated successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Prospecting $prospecting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prospecting $prospecting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prospecting $prospecting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prospecting $prospecting)
    {
        //
    }
}
