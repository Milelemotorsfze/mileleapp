<?php

namespace App\Http\Controllers;

use App\Models\PurchasingOrder;
use App\Models\PurchasingOrderItems;
use Illuminate\Http\Request;
use App\Models\Varaint;
use App\Models\Supplier;
use App\Models\Vehicles;

class PurchasingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PurchasingOrder::with('purchasing_order_items')->get();
        return view('warehouse.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $suppliers = Supplier::with('supplierTypes')
        ->whereHas('supplierTypes', function ($query) {
            $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
        })
        ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
        ->get();

    $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();

    return view('warehouse.create', compact('variants', 'suppliers'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $poNumber = $request->input('po_number');
        $existingPO = PurchasingOrder::where('po_number', $poNumber)->first();
        if ($existingPO) {
            return redirect()->back()
            ->withInput($request->all())
            ->withErrors(['po_number' => 'PO number already exists']);
        }
        $poDate = $request->input('po_date');
        $poNumber = $request->input('po_number');
        $suppliers_id = $request->input('suppliers_id');
        $purchasingOrder = new PurchasingOrder();
        $purchasingOrder->po_date = $poDate;
        $purchasingOrder->po_number = $poNumber;
        $purchasingOrder->suppliers_id = $suppliers_id;
        $purchasingOrder->save();
        $purchasingOrderId = $purchasingOrder->id;
        $variantNames = $request->input('variant_id');
        if($variantNames != null)
        {
        $variantIds = Varaint::whereIn('name', $variantNames)->pluck('id')->all();
        foreach ($variantIds as $variantId) {
            $purchasingOrderItem = new PurchasingOrderItems();
            $purchasingOrderItem->variant_id = $variantId;
            $purchasingOrderItem->purchasing_order_id = $purchasingOrderId;
            $purchasingOrderItem->save();
        }
        $vins = $request->input('vin');
        $ex_colours = $request->input('ex_colour');
        $int_colours = $request->input('int_colour');
        $count = count($variantNames);
        foreach ($variantNames as $key => $variantName) {
        if ($variantName === null && $key === $count - 1) {
        continue;
        }
        $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
        $vin = $vins[$key];
        $ex_colour = $ex_colours[$key];
        $int_colour = $int_colours[$key];
        $vehicle = new Vehicles();
        $vehicle->varaints_id = $variantId;
        $vehicle->vin = $vin;
        $vehicle->ex_colour = $ex_colour;
        $vehicle->int_colour = $int_colour;
        $vehicle->purchasing_order_id = $purchasingOrderId;
        $vehicle->save();
    }
    }
    return redirect()->route('purchasing-order.index')->with('success', 'PO Created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(PurchasingOrder $purchasingOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
    $supplierName = Supplier::where('id', $purchasingOrder->suppliers_id)->value('supplier');
    return view('warehouse.edit', compact('purchasingOrder', 'variants', 'vehicles', 'supplierName'));
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $variantIds = $request->input('id');
    $newVins = $request->input('oldvin');
    $newex_colours = $request->input('oldex_colour');
    $newint_colours = $request->input('oldint_colour');
    foreach ($variantIds as $index => $variantId) {
        $vehicle = Vehicles::find($variantId);
        if ($vehicle) {
            $vehicle->vin = $newVins[$index];
            $vehicle->ex_colour = $newex_colours[$index];
            $vehicle->int_colour = $newint_colours[$index];
            $vehicle->save();
        }
    }
    $purchasingOrderId = $id;
    $variantNames = $request->input('variant_id');
    if($variantNames != null)
        {
        $variantIds = Varaint::whereIn('name', $variantNames)->pluck('id')->all();
        foreach ($variantIds as $variantId) {
            $purchasingOrderItem = new PurchasingOrderItems();
            $purchasingOrderItem->variant_id = $variantId;
            $purchasingOrderItem->purchasing_order_id = $purchasingOrderId;
            $purchasingOrderItem->save();
        }
        $vins = $request->input('vin');
        $ex_colours = $request->input('ex_colour');
        $int_colours = $request->input('int_colour');
        $count = count($variantNames);
        foreach ($variantNames as $key => $variantName) {
        if ($variantName === null && $key === $count - 1) {
        continue;
        }
        $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
        $ex_colour = $ex_colours[$key];
        $int_colour = $int_colours[$key];
        $vin = $vins[$key];
        $vehicle = new Vehicles();
        $vehicle->varaints_id = $variantId;
        $vehicle->vin = $vin;
        $vehicle->ex_colour = $ex_colour;
        $vehicle->int_colour = $int_colour;
        $vehicle->purchasing_order_id = $purchasingOrderId;
        $vehicle->save();
    }
    }
    return redirect()->route('purchasing-order.index')->with('success', 'PO Update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchasingOrder $purchasingOrder)
    {
        //
    }
    public function checkPONumber(Request $request)
    {
        $poNumber = $request->input('poNumber');
        $existingPO = PurchasingOrder::where('po_number', $poNumber)->first();
        if ($existingPO) {
            return response()->json(['error' => 'PO number already exists'], 422);
        }
        return response()->json(['success' => 'PO number is valid'], 200);
    }
}
