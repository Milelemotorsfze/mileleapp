<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LetterOfIndent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Monarobase\CountryList\CountryListFacade;

class LetterOfIndentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $letterOfIndents = LetterOfIndent::all();


        return view('letter_of_indents.index', compact('letterOfIndents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = CountryListFacade::getList('en');
        $customers = Customer::all();

        return view('letter_of_indents.create',compact('countries','customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'category' => 'required',
            'date' => 'required'
        ]);

        $LOI = new LetterOfIndent();

        $LOI->customer_id = $request->customer_id;
        $LOI->date = Carbon::createFromFormat('Y-m-d', $request->date);
        $LOI->category = $request->category;
        $LOI->submission_status = LetterOfIndent::LOI_SUBMISION_STATUS;
        $LOI->status = LetterOfIndent::LOI_STATUS;
        $LOI->save();

        return redirect()->route('letter-of-indent-items.create',['id' => $LOI->id]);
    }
    public function getCustomers(Request $request)
    {
        $customers = Customer::where('country', $request->country)
            ->where('type', $request->customer_type)
            ->get();

        return $customers;
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
