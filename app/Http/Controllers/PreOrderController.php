<?php

namespace App\Http\Controllers;

use App\Models\PreOrder;
use App\Models\UserActivities;
use App\Models\Brand;
use App\Models\ColorCode;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use App\Models\QuotationItem;
use App\Models\QuotationDetail;
use App\Models\MasterModelLines;
use App\Models\Varaint;
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
            $data = PreOrder::select( [
                    'vehicles.id',
                    'warehouse.name as location',
                     DB::raw("DATE_FORMAT(purchasing_order.po_date, '%d-%b-%Y') as po_date"),
                    'vehicles.ppmmyyy',
                    'vehicles.vin',
                    'brands.brand_name',
                    'varaints.name as variant',
                    'varaints.model_detail',
                    'varaints.detail',
                    'varaints.seat',
                    'varaints.upholestry',
                    'varaints.steering',
                    'varaints.my',
                    'varaints.fuel_type',
                    'varaints.gearbox',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'grn.grn_number',
                    DB::raw("DATE_FORMAT(grn.date, '%d-%b-%Y') as date"),
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('inspection', 'vehicles.id', '=', 'inspection.vehicle_id')
                ->whereNull('inspection.id')
                ->whereNull('vehicles.inspection_date')
                ->whereNull('vehicles.gdn_id')
                ->whereNotNull('vehicles.grn_id');
                $data = $data->groupBy('vehicles.id');
            }
                return DataTables::of($data)
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
}
