<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open Customer List Page');

        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open Customer Create Page');

        $countries = Country::pluck('name');
        return view('customer.create', compact('countries'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('New Customer Created');

        $this->validate($request, [
            'name' => 'required',
            'country' => 'required',
            'type' => 'required',
        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->company_name = $request->company_name;
        $customer->country = $request->country;
        $customer->type = $request->type;
        $customer->address = $request->address;
        $customer->save();

        return redirect()->route('customers.index')->with('success','Customer Created Successfully.');
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
        (new UserActivityController)->createActivity('Open Customer Edit Page');

         $customer = Customer::find($id);
         $countries = Country::pluck('name');

         return view('customer.edit', compact('customer','countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Customer Detail Updated');

        $this->validate($request, [
            'name' => 'required',
            'country' => 'required',
            'type' => 'required',
        ]);

        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->company_name = $request->company_name;
        $customer->country = $request->country;
        $customer->type = $request->type;
        $customer->address = $request->address;
        $customer->save();

        return redirect()->route('customers.index')->with('success','Customer Created Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
