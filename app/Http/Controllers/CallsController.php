<?php

namespace App\Http\Controllers;

use App\Models\calls;
use Illuminate\Http\Request;

class CallsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Calls::orderBy('status','DESC')->whereIn('status',['new','active'])->get();
        $convertedleads = Calls::where('status','Converted To Leads')->get();
        return view('calls.index',compact('data','convertedleads'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'sales_person' => 'required',
            'email' => 'required|email|unique:users,email',
        ]);
        $data = [
            'date' => $request->input('date'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'sales_person' => $request->input('sales_person'),
            'remarks' => $request->input('remarks'),
            'phone' => $request->input('phone'),
            'user_id' => $request->input('user_id'),
        ];
        $model = new Calls($data);
        $model->save();
        return redirect()->route('calls.index')
        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(calls $calls)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(calls $calls)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, calls $calls)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(calls $calls)
    {
        //
    }
}
