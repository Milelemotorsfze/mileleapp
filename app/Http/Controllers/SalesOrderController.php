<?php

namespace App\Http\Controllers;

use App\Models\So;
use App\Models\Quotation;
use App\Models\Closed;
use App\Models\Calls;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\QuotationItem;
use App\Models\Brand;
use App\Models\Varaint;
use App\Models\MasterModelLines;

use Illuminate\Http\Request;

class SalesOrderController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesOrder $salesOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $salesOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        //
    }
    public function createsalesorder($callId) {
        // Retrieve the quotation based on callId
        $quotation = Quotation::where('calls_id', $callId)->first();
        // Initialize an empty array to store the resulting vehicles
        $vehicles = [];
        // Check if the quotation exists
        if ($quotation) {
            // Retrieve quotation items based on quotation_id and reference_type
            $quotationItems = QuotationItem::where('quotation_id', $quotation->id)
                ->whereIn('reference_type', [
                    'App\Models\Varaint',
                    'App\Models\MasterModelLines',
                    'App\Models\Brand'
                ])->get();
                // Loop through each quotation item
            foreach ($quotationItems as $item) {
                switch ($item->reference_type) {
                    case 'App\Models\Varaint':
                    // Retrieve vehicles associated with this variant
                    $variantId = $item->reference_id;
                    $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                    $vehicles[$item->id] = $variantVehicles;
                    break;
                case 'App\Models\MasterModelLines':
                    // Retrieve variants associated with this MasterModelLines
                    $variants = Variant::where('master_model_lines_id', $item->reference_id)->get();
                    foreach ($variants as $variant) {
                        // Retrieve vehicles associated with each variant
                        $variantId = $variant->id;
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                        $vehicles[$item->id] = $variantVehicles;
                    }
                    break;
                case 'App\Models\Brand':
                    // Retrieve variants associated with this Brand
                    $variants = Variant::where('brand_id', $item->reference_id)->get();
                    foreach ($variants as $variant) {
                        // Retrieve vehicles associated with each variant
                        $variantId = $variant->id;
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                        $vehicles[$item->id] = $variantVehicles;
                    }
                    break;
                default:
                    // Handle other reference types if needed
                    break;
                }
                    }
                    }  
                    return view('salesorder.create', compact('vehicles', 'quotationItems', 'quotation')); 
            }  
            public function storesalesorder(Request $request, $quotationId)
            {
                $so = New So();
                $so->quotation_id = $quotationId;
                $so->sales_person_id = Auth::id();
                $so->so_number = $request->input('so_number');
                $so->so_date = $request->input('so_date');
                $so->notes = $request->input('notes');
                $so->total = $request->input('total_payment');
                $so->receiving = $request->input('receiving_payment');
                $so->paidinso = $request->input('payment_so');
                $so->paidinperforma = $request->input('advance_payment_performa');
                $so->save();
                $qoutation = Quotation::find($quotationId);
                $calls = Calls::find($qoutation->calls_id);
                $calls->status = "Closed";
                $calls->save();
                $closed = New Closed();
                $closed->date = $request->input('so_date');
                $closed->sales_notes = $request->input('notes');
                $closed->call_id = $calls->id;
                $closed->created_by = Auth::id();
                $closed->dealvalues = $request->input('total_payment');
                $closed->currency = $request->input('so_date');
                $closed->so_id = $so->id;
                $closed->save();
                $vins = $request->input('vehicle_vin');
                Vehicles::whereIn('vin', $vins)->update(['so_id' => $so->id]);
                return redirect()->route('dailyleads.index')->with('success', 'Sales Order created successfully.'); 
            }  
        }
