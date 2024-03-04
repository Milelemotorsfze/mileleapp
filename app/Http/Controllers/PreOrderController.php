<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\PreOrderPos;
use App\Models\UserActivities;
use App\Models\Brand;
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
                    'so.so_number',
                    'so.notes',
                    'master_model_lines.model_line as model_line',
                    'pre_orders_items.qty',
                    'pre_orders_items.description',
                    'countries.name as countryname',
                    'color_codes_exterior.name as exterior', 
                    'color_codes_interior.name as interior', 
                    'pre_orders_items.modelyear',
                    'brands.brand_name',
                    'users.name as salesperson'
                ])
                ->leftJoin('pre_orders', 'pre_orders_items.preorder_id', '=', 'pre_orders.id')
                ->leftJoin('so', 'pre_orders.quotations_id', '=', 'so.quotation_id')
                ->leftJoin('users', 'pre_orders.requested_by', '=', 'users.id')
                ->leftJoin('master_model_lines', 'pre_orders_items.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->leftJoin('countries', 'pre_orders_items.countries_id', '=', 'countries.id')
                ->leftJoin('color_codes as color_codes_exterior', 'pre_orders_items.ex_colour', '=', 'color_codes_exterior.id') // distinct alias for exterior color
                ->leftJoin('color_codes as color_codes_interior', 'pre_orders_items.int_colour', '=', 'color_codes_interior.id') // distinct alias for interior color
                ->where('pre_orders_items.status', 'Approved')
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
    $excolourcode = ColorCode::where('belong_to', "ex")->get();
    $intcolourcode = ColorCode::where('belong_to', "int")->get();
    $quotation = Quotation::where('calls_id', $callId)->first();
    $modelLines = [];
    $quotationItems = QuotationItem::where('quotation_id', $quotation->id)
                    ->whereIn('reference_type', [
                        'App\Models\Varaint',
                        'App\Models\MasterModelLines',
                        'App\Models\Brand'
                    ])->get();
                foreach ($quotationItems as $item) {
                    switch ($item->reference_type) {
                        case 'App\Models\Varaint':
                        $variant = Varaint::find($item->reference_id);
                        if ($variant) {
                            $master_model_lines = $variant->master_model_lines;
                            if ($master_model_lines) {
                                $modelLines[$item->id][$variant->master_model_lines_id] = $master_model_lines->model_line;
                            }
                        }
                        break;
                    case 'App\Models\MasterModelLines':
                        $masterModelLinesId = $item->reference_id;
                        // Fetch all model lines associated with this MasterModelLines
                        $masterModelLines = MasterModelLines::where('id', $masterModelLinesId)->get();
                        foreach ($masterModelLines as $masterModelLine) {
                            $modelLines[$item->id][$masterModelLine->id] = $masterModelLine->model_line;
                        }
                        break;
                    case 'App\Models\Brand':
                        $brandId = $item->reference_id;
                        $masterModelLines = MasterModelLines::where('brand_id', $brandId)->get();
                        foreach ($masterModelLines as $masterModelLine) {
                            $modelLines[$item->id][$masterModelLine->id] = $masterModelLine->model_line;
                        }
                        break;
                    default:
                        break;
                    }
                        }
    return view('preorder.create', compact('modelLines', 'quotation', 'intcolourcode', 'excolourcode')); 
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
                foreach ($request->master_model_lines_id as $key => $modelLineId) {
                    $preorderItem = new PreOrdersItems();
                    $preorderItem->preorder_id = $preorder->id;
                    $preorderItem->countries_id = $quotationdetails->country_id ?? null;
                    $preorderItem->master_model_lines_id = $modelLineId;
                    $preorderItem->int_colour = $request->int_colour[$key];
                    $preorderItem->ex_colour = $request->ex_colour[$key];
                    $preorderItem->modelyear = $request->modelyear[$key];
                    $preorderItem->qty = $request->qty[$key];
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
                info($po_number);
                $poNumbers = PurchasingOrder::where('po_number', $po_number)->first();
                if ($poNumbers) {
                    $poid = $poNumbers->id; 
                    info($poid);
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
