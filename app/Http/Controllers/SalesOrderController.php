<?php

namespace App\Http\Controllers;

use App\Models\So;
use App\Models\Quotation;
use App\Models\Booking;
use App\Models\BookingRequest;
use App\Models\Closed;
use App\Models\Calls;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\QuotationItem;
use Carbon\Carbon;
use App\Models\Brand;
use App\Models\User;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\Varaint;
use App\Models\PreOrder;
use App\Models\PreOrdersItems;
use App\Models\QuotationDetail;
use App\Models\Soitems;
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
        $quotation = Quotation::where('calls_id', $callId)->first();
        $calls = Calls::find($callId);
        $customerdetails = QuotationDetail::with('country', 'shippingPort', 'shippingPortOfLoad', 'paymentterms')->where('quotation_id', $quotation->id)->first();
        $vehicles = [];
        if ($quotation) {
            $quotationItems = QuotationItem::where('quotation_id', $quotation->id)
                ->whereIn('reference_type', [
                    'App\Models\Varaint',
                    'App\Models\MasterModelLines',
                    'App\Models\Brand'
                ])->get();
            foreach ($quotationItems as $item) {
                switch ($item->reference_type) {
                    case 'App\Models\Varaint':
                    $variantId = $item->reference_id;
                    $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                    $vehicles[$item->id] = $variantVehicles;
                    break;
                case 'App\Models\MasterModelLines':
                    $variants = Varaint::where('master_model_lines_id', $item->reference_id)->get();
                    foreach ($variants as $variant) {
                        $variantId = $variant->id;
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                        $vehicles[$item->id] = $variantVehicles;
                    }
                    break;
                case 'App\Models\Brand':
                    $variants = Varaint::where('brand_id', $item->reference_id)->get();
                    foreach ($variants as $variant) {
                        $variantId = $variant->id;
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                        $vehicles[$item->id] = $variantVehicles;
                    }
                    break;
                default:
                    break;
                }
                    }
                    }
                    $saleperson = User::find($calls->sales_person);
                    $empProfile = EmployeeProfile::where('user_id', $calls->sales_person)->first();
                    return view('salesorder.create', compact('vehicles', 'quotationItems', 'quotation', 'calls', 'customerdetails', 'empProfile', 'saleperson')); 
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
                $closed->currency = $qoutation->currency;
                $closed->so_id = $so->id;
                $closed->save();
                $vins = $request->input('vehicle_vin');
                $selectedVinsWithNull = [];
                $selectedVinsWithoutNull = [];
                foreach ($vins as $quotationItemId => $selectedVins) {
                    foreach ($selectedVins as $selectedVin) {
                        if ($selectedVin === null) {
                            $selectedVinsWithNull[$quotationItemId][] = $selectedVin;
                        } else {
                            $selectedVinsWithoutNull[$quotationItemId][] = $selectedVin;
                        }
                    }
                }
                $allVinsWithoutNull = [];
                foreach ($selectedVinsWithoutNull as $selectedVins) {
                    $allVinsWithoutNull = array_merge($allVinsWithoutNull, $selectedVins);
                }
                Vehicles::whereIn('vin', $allVinsWithoutNull)->update(['so_id' => $so->id]);
                foreach ($selectedVinsWithoutNull as $quotationItemId => $selectedVins) {
                    foreach ($selectedVins as $selectedVin) {
                        $vehicle = Vehicles::where('vin', $selectedVin)->first();
                        $soVinRelationship = new Soitems();
                        $soVinRelationship->so_id = $so->id;
                        $soVinRelationship->quotation_items_id = $quotationItemId;
                        $soVinRelationship->vehicles_id = $vehicle->id;
                        $soVinRelationship->save();
                        $existingbookingpending = BookingRequest::where('quotation_items_id', $quotationItemId)->where('status', "New")->first();
                        $existingbookingapproved = BookingRequest::where('quotation_items_id', $quotationItemId)->where('status', "Approved")->first();
                        if($existingbookingpending)
                        {
                            $existingbookingpending->days = "10";
                            $existingbookingpending->save();
                        }
                        else if ($existingbookingapproved)
                        {
                            $existingbookingapproved->days = "10";
                            $existingbookingapproved->save();
                            $updatebooking = Booking::where('booking_requests_id',$existingbookingapproved->id)->whereDate('booking_end_date', '>', now())->first();
                            if($updatebooking)
                            {
                            $updatebooking->booking_end_date  = Carbon::now()->addDays(10);
                            $updatebooking->save();
                            $vehicle->reservation_end_date = Carbon::now()->addDays(10);
                            $vehicle->save();
                            }
                            else
                            {
                                $booking = New BookingRequest();
                                $booking->vehicle_id = $vehicle->id;
                                $booking->calls_id = $calls->id;
                                $booking->created_by = Auth::id();
                                $booking->status = "New";
                                $booking->days = "10";
                                $booking->save();   
                            }
                        }
                        else
                        {
                        $booking = New BookingRequest();
                        $booking->vehicle_id = $vehicle->id;
                        $booking->calls_id = $calls->id;
                        $booking->created_by = Auth::id();
                        $booking->status = "New";
                        $booking->days = "10";
                        $booking->save();
                        }
                    }
                }
                // if($selectedVinsWithNull)
                // {
                //     $pre_order = new PreOrder();
                //     $pre_order->quotations_id = $quotationId;
                //     $pre_order->requested_by  = Auth::id();
                //     $pre_order->save();
                //     foreach ($selectedVinsWithNull as $quotationItemId => $selectedVins) {
                //         $totalNullVins = count($selectedVins);
                //         $quotationitems = QuotationItem::find($quotationItemId);
                //         $quotationdetails = QuotationDetail::with('country')->where('quotation_id', $quotationId)->first();
                //         $preOrderItem = new PreOrdersItems();
                //         $preOrderItem->countries_id = $quotationdetails->country->id;
                //         $preOrderItem->description = $quotationitems->description; 
                //         $preOrderItem->master_model_lines_id = $quotationitems->model_line_id;
                //         $preOrderItem->preorder_id = $pre_order->id;
                //         $preOrderItem->qty = $totalNullVins;
                //         $preOrderItem->save();
                //     }  
                // }
                return redirect()->route('dailyleads.index')->with('success', 'Sales Order created successfully.'); 
            } 
        public function updatesalesorder ($id) 
        {
            $quotation = Quotation::where('calls_id', $id)->first();
            $calls = Calls::find($id);
            $sodetails = So::where('quotation_id', $quotation->id)->first();
            $soitems = Soitems::with('vehicle') // Ensure that vehicle is eager loaded
                      ->where('so_id', $sodetails->id)
                      ->get();
            $customerdetails = QuotationDetail::with('country', 'shippingPort', 'shippingPortOfLoad', 'paymentterms')->where('quotation_id', $quotation->id)->first();
            $vehicles = [];
            if ($quotation) {
                $quotationItems = QuotationItem::where('quotation_id', $quotation->id)
                    ->whereIn('reference_type', [
                        'App\Models\Varaint',
                        'App\Models\MasterModelLines',
                        'App\Models\Brand'
                    ])->get();
                foreach ($quotationItems as $item) {
                    switch ($item->reference_type) {
                        case 'App\Models\Varaint':
                        $variantId = $item->reference_id;
                        $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                        $vehicles[$item->id] = $variantVehicles;
                        break;
                    case 'App\Models\MasterModelLines':
                        $variants = Varaint::where('master_model_lines_id', $item->reference_id)->get();
                        foreach ($variants as $variant) {
                            $variantId = $variant->id;
                            $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                            $vehicles[$item->id] = $variantVehicles;
                        }
                        break;
                    case 'App\Models\Brand':
                        $variants = Varaint::where('brand_id', $item->reference_id)->get();
                        foreach ($variants as $variant) {
                            $variantId = $variant->id;
                            $variantVehicles = DB::table('vehicles')->where('varaints_id', $variantId)->get()->toArray();
                            $vehicles[$item->id] = $variantVehicles;
                        }
                        break;
                    default:
                        break;
                    }
                        }
                        } 
                        $saleperson = User::find($calls->sales_person);
                        $empProfile = EmployeeProfile::where('user_id', $calls->sales_person)->first(); 
                        return view('salesorder.update', compact('vehicles', 'quotationItems', 'quotation', 'calls', 'customerdetails','sodetails', 'soitems', 'empProfile', 'saleperson'));  
        }
        public function storesalesorderupdate(Request $request, $quotationId)
{
    // Validate and retrieve the Sales Order ID
    $so_id = $request->input('so_id');
    $so = So::findOrFail($so_id);

    // Update the Sales Order fields
    $so->so_number = $request->input('so_number');
    $so->so_date = $request->input('so_date');
    $so->notes = $request->input('notes');
    $so->total = $request->input('total_payment');
    $so->receiving = $request->input('receiving_payment');
    $so->paidinso = $request->input('payment_so');
    $so->paidinperforma = $request->input('advance_payment_performa');
    $so->save();

    // Delete existing Soitems records related to the Sales Order ID
    Soitems::where('so_id', $so->id)->delete();

    // Process the selected VINs
    $vins = $request->input('vehicle_vin');
    $selectedVinsWithNull = [];
    $selectedVinsWithoutNull = [];

    // Separate VINs with null and without null
    foreach ($vins as $quotationItemId => $selectedVins) {
        foreach ($selectedVins as $selectedVin) {
            if (empty($selectedVin)) {
                $selectedVinsWithNull[$quotationItemId][] = $selectedVin;
            } else {
                $selectedVinsWithoutNull[$quotationItemId][] = $selectedVin;
            }
        }
    }

    // Update the vehicles with the Sales Order ID
    $allVinsWithoutNull = [];
    foreach ($selectedVinsWithoutNull as $selectedVins) {
        $allVinsWithoutNull = array_merge($allVinsWithoutNull, $selectedVins);
    }
    Vehicles::whereIn('id', $allVinsWithoutNull)->update(['so_id' => $so->id]);

    // Insert new Soitems records with the selected VINs
    foreach ($selectedVinsWithoutNull as $quotationItemId => $selectedVins) {
        foreach ($selectedVins as $selectedVin) {
            $vehicle = Vehicles::where('id', $selectedVin)->firstOrFail();
            $soVinRelationship = new Soitems([
                'so_id' => $so->id,
                'quotation_items_id' => $quotationItemId,
                'vehicles_id' => $vehicle->id
            ]);
            $soVinRelationship->save();

            // Handle BookingRequest updates
            $existingBookingPending = BookingRequest::where('quotation_items_id', $quotationItemId)
                ->where('status', "New")->first();
            $existingBookingApproved = BookingRequest::where('quotation_items_id', $quotationItemId)
                ->where('status', "Approved")->first();

            if ($existingBookingPending) {
                $existingBookingPending->days = "10";
                $existingBookingPending->save();
            } elseif ($existingBookingApproved) {
                $existingBookingApproved->days = "10";
                $existingBookingApproved->save();

                $updateBooking = Booking::where('booking_requests_id', $existingBookingApproved->id)
                    ->whereDate('booking_end_date', '>', now())->first();

                if ($updateBooking) {
                    $updateBooking->booking_end_date = Carbon::now()->addDays(10);
                    $updateBooking->save();
                    $vehicle->reservation_end_date = Carbon::now()->addDays(10);
                    $vehicle->save();
                } else {
                    $booking = new BookingRequest();
                    $booking->vehicle_id = $vehicle->id;
                    $booking->calls_id = $so->calls_id;
                    $booking->created_by = Auth::id();
                    $booking->status = "New";
                    $booking->days = "10";
                    $booking->save();
                }
            } else {
                $booking = new BookingRequest();
                $booking->vehicle_id = $vehicle->id;
                $booking->calls_id = $so->calls_id;
                $booking->created_by = Auth::id();
                $booking->status = "New";
                $booking->days = "10";
                $booking->save();
            }
        }
    }
    return redirect()->route('dailyleads.index')->with('success', 'Sales Order updated successfully.');
}
        }
