<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Enum\Enum;

class DemandPlanningSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('demand_planning_suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:suppliers,supplier|max:255'
        ]);

        DB::beginTransaction();
        $supplier = new Supplier();
        $supplier->supplier = $request->name;
        $supplier->save();

        $supplierType = new SupplierType();
        $supplierType->supplier_id = $supplier->id;
        $supplierType->supplier_type = Supplier::SUPPLIER_TYPE_DEMAND_PLANNING;
        $supplierType->created_by = Auth::id();
        $supplierType->save();

        DB::commit();

        return redirect()->back()->with('message','Supplier created successfully.');
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
