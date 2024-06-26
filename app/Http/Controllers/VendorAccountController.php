<?php

namespace App\Http\Controllers;
use App\Models\SupplierAccount;
use App\Models\SupplierAccountTransaction;
use Illuminate\Http\Request;

class VendorAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('View Vendor Accounts');
        $accounts = SupplierAccount::with('supplier')->get();
        return view('suppliers.accounts.index', compact('accounts'));
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
    public function view($id)
    {
        $transitions = SupplierAccountTransaction::where('supplier_account_id', $id)->with('purchaseOrder')->get();
        $accounts = SupplierAccount::with('supplier')->where('id', $id)->first();
        return view('suppliers.accounts.transitions', compact('transitions', 'accounts'));
    }
}
