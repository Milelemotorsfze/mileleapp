<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankMaster;
use App\Models\BankAccounts;
use App\Http\Controllers\UserActivityController;
class BanksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('View Bank Accounts');
        $banks = BankMaster::all();
        return view('banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Create Bank Accounts');
        return view('banks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
    $validatedData = $request->validate([
        'bank_name' => 'required|string|max:255',
        'branch_name' => 'nullable|string|max:255',
        'contact_person' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'contact_number' => 'required|numeric',
    ]);
    // Create a new bank record using the validated data
    BankMaster::create($validatedData);
    // Redirect to a specific route or return a response
    return redirect()->route('banks.index')->with('success', 'Bank details saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    $bank = BankMaster::findOrFail($id);
    return view('banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'contact_number' => 'required|numeric',
        ]);
        $bank = BankMaster::findOrFail($id);
        $bank->update($request->all());
        return redirect()->route('banks.index')->with('success', 'Bank updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bankAccounts = BankAccounts::where('bank_master_id', $id)->count();
        if ($bankAccounts > 0) {
            return redirect()->back()->with('error', 'Cannot delete this bank because it has associated bank accounts.');
        }
        $bank = BankMaster::findOrFail($id);
        $bank->delete();
        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
    }
    public function getBanks()
    {
        $banks = BankMaster::all();
        return response()->json($banks);
    }
}
