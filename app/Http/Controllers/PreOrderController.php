<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\PreOrderPos;
use App\Models\UserActivities;
use App\Models\Brand;
use App\Models\clients;
use App\Models\ColorCode;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use App\Models\QuotationItem;
use App\Models\PurchasingOrder;
use App\Models\QuotationDetail;
use App\Models\MasterModelLines;
use App\Models\Varaint;
use Yajra\DataTables\DataTables;
use App\Models\PreOrdersItems;
use Illuminate\Http\Request;

class PreOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Pre Order View Purchasing";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            if($status === "Pending")
            {
                $preorders = PreOrdersItems::select([
                    'pre_orders.id as pre_order_number',
                    'pre_orders.status',
                    'pre_orders_items.id',
                    'pre_orders_items.qty',
                    'pre_orders_items.description',
                    'countries.name as countryname',
                    'varaints.name',
                    'quotations.id as quotationsid',
                    'users.name as salesperson'
                ])
                ->leftJoin('pre_orders', 'pre_orders_items.preorder_id', '=', 'pre_orders.id')
                ->leftJoin('quotations', 'pre_orders.quotations_id', '=', 'quotations.quotation_id')
                ->leftJoin('quotation_details', 'pre_orders.quotations_id', '=', 'quotation_details.quotation_id')
                ->leftJoin('varaints', 'pre_orders_items.variant_id', '=', 'varaints.id')
                ->leftJoin('countries', 'pre_orders_items.countries_id', '=', 'countries.id')
                ->leftJoin('users', 'pre_orders.requested_by', '=', 'users.id')
                ->groupby('pre_orders_items.id');
            }
                return DataTables::of($preorders)
                ->toJson();
        }
        return view('preorder.index');
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
    public function show(PreOrder $preOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PreOrder $preOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PreOrder $preOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PreOrder $preOrder)
    {
        //
    }
    public function createpreorder($callId) {
    $quotation = Quotation::where('calls_id', $callId)->first();
    $calls = Quotation::where('calls_id', $callId)->first();
    $quotationItems = QuotationItem::where('quotation_id', $quotation->id)->get();
    $variants = collect();
    foreach ($quotationItems as $item) {
        if ($item->reference_type === 'App\Models\Varaint') {
            $variant = Varaint::where('id', $item->reference_id)->first();
        } elseif ($item->reference_type === 'App\Models\MasterModelLines') {
            $variant = Varaint::where('master_model_lines_id', $item->reference_id)->first();
        } else {
            continue;
        }
        if ($variant && !$variants->contains('id', $variant->id)) {
            $variants->push($variant);
        }
    }
    return view('preorder.create', compact('variants', 'quotation')); 
    }
        public function storepreorder(Request $request, $quotationId)
            {
                $quotationdetails = QuotationDetail::where('quotation_id',$quotationId)->first();
                $preorder = New PreOrder();
                $preorder->quotations_id = $quotationId;
                $preorder->requested_by = Auth::id();
                $preorder->status = "New";
                $preorder->save();
                $preorder_items = New PreOrdersItems();
                foreach ($request->variant_id as $key => $variant_id) {
                    $preorderItem = new PreOrdersItems();
                    $preorderItem->preorder_id = $preorder->id;
                    $preorderItem->countries_id = $quotationdetails->country_id ?? null;
                    $preorderItem->variant_id = $variant_id;
                    $preorderItem->qty = $request->qty[$key];
                    $preorderItem->notes = $request->notes[$key];
                    $preorderItem->status = "New";
                    $preorderItem->save();
                }
            return redirect()->route('dailyleads.index')->with('success', 'Pre Order created successfully.');
            }
            public function getpoforpreorder()
            {
                $poNumbers = PurchasingOrder::pluck('po_number')->toArray();
                return response()->json($poNumbers);
        }
        public function savepolistpreorder(Request $request)
        {
            $polist = $request->input('po_numbers');
            $pre_orders_items = $request->input('Preorder_id_input');
            $notes = $request->input('notes');
            foreach ($polist as $po_number) {
                $poNumbers = PurchasingOrder::where('po_number', $po_number)->first();
                if ($poNumbers) {
                    $poid = $poNumbers->id; 
                    $preOrderPos = new PreOrderPos();
                    $preOrderPos->purchasing_order_id = $poid;
                    $preOrderPos->pre_orders_items_id = $pre_orders_items;
                    $preOrderPos->save();
                } else {
                    info("PurchasingOrder not found for po_number: " . $po_number);
                }
            }
            $PreOrdersItems = PreOrdersItems::where('id', $pre_orders_items)->first();
            $PreOrdersItems->status = "Under-Processing";
            $PreOrdersItems->notes = $notes;
            $PreOrdersItems->save();
            return response()->json(['message' => 'PO list saved successfully'], 200);
        }
        }
