<?php

namespace App\Http\Controllers;

use App\Models\ApprovedLetterOfIndentItem;
use App\Models\ColorCode;
use App\Models\Supplier;
use App\Models\SupplierType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandPlanningPurchaseOrderController extends Controller
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
    public function create(Request $request)
    {
        $approvedLOIItem = ApprovedLetterOfIndentItem::find($request->id);
        $vendor = $approvedLOIItem->letterOfIndentItem->LOI->supplier_id ?? '';
//        return $request->all();
        $vendors = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query){
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->get();
        $pfiVehicleVariants = ApprovedLetterOfIndentItem::select('*', DB::raw('sum(quantity) as quantity'))
            ->where('pfi_id', $request->id)
            ->groupBy('letter_of_indent_item_id')
            ->get();
        $exColours = ColorCode::where('belong_to', 'ex')->pluck('name', 'id')->toArray();
        $intColours = ColorCode::where('belong_to', 'int')->pluck('name', 'id')->toArray();
        return view('purchase-order.create', compact('vendors','pfiVehicleVariants',
            'exColours','intColours','vendor'));
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
