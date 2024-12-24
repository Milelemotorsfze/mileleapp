<?php

namespace App\Http\Controllers;

use App\Models\So;
use App\Models\Quotation;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\UserActivities;
use App\Models\BookingRequest;
use App\Models\Closed;
use App\Models\Calls;
use App\Models\Vehicles;
use Yajra\DataTables\DataTables;
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
use App\Models\Solog;
use App\Models\MasterModelLines;

use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "Open Sales Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            if($status === "SalesOrder")
            {
                $id = Auth::user()->id;
                // info($id);
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
        if($hasPermission)
        {
                $data = So::select([
                    'calls.name as customername',
                    'calls.email',
                    'calls.phone',
                    'quotations.created_at',
                    'quotations.deal_value',
                    'quotations.sales_notes',
                    'quotations.file_path',
                    'users.name',
                    'so.so_number',
                    'so.so_date',
                    'quotations.calls_id',
                ])
                ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
                ->leftJoin('users', 'quotations.created_by', '=', 'users.id')
                ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
                ->groupby('so.id')
                ->orderBy('so.so_date', 'desc');
        }
        else
        {
            $data = So::select([
                'calls.name as customername',
                'calls.email',
                'calls.phone',
                'quotations.created_at',
                'quotations.deal_value',
                'quotations.sales_notes',
                'quotations.file_path',
                'users.name',
                'so.so_number',
                'so.so_date',
                'quotations.calls_id',
            ])
            ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
            ->leftJoin('users', 'quotations.created_by', '=', 'users.id')
            ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
            ->where('calls.sales_person', $id)
            ->groupby('so.id')
            ->orderBy('so.so_date', 'desc');  
        }
    }  
            if ($data) {
                return DataTables::of($data)->toJson();
            }
            }
        return view('dailyleads.salesorder');
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
                    $saleperson = User::find($quotation->created_by);
                    $empProfile = EmployeeProfile::where('user_id', $quotation->created_by)->first();
                    return view('salesorder.create', compact('vehicles', 'quotationItems', 'quotation', 'calls', 'customerdetails', 'empProfile', 'saleperson')); 
            }  
            public function storesalesorder(Request $request, $quotationId)
            {
                $qoutation = Quotation::find($quotationId);
                $so = New So();
                $so->quotation_id = $quotationId;
                $so->sales_person_id = $qoutation->created_by;
                $so_number = $request->input('so_number'); // Get the input value
                $so->so_number = 'SO-' . $so_number;    // Concatenate "SO-00" with the input value
                $so->so_date = $request->input('so_date');
                $so->notes = $request->input('notes');
                $so->total = $request->input('total_payment');
                $so->receiving = $request->input('receiving_payment');
                $so->paidinso = $request->input('payment_so');
                $so->paidinperforma = $request->input('advance_payment_performa');
                $so->created_by = auth()->id();
                $so->created_at = Carbon::now();
                $so->updated_at = Carbon::now();
                $so->save();
                $calls = Calls::find($qoutation->calls_id);
                $calls->status = "Closed";
                $calls->save();
                $closed = New Closed();
                $closed->date = $request->input('so_date');
                $closed->sales_notes = $request->input('notes');
                $closed->call_id = $calls->id;
                $closed->created_by = $qoutation->created_by;
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
                $solog = New Solog();
                $solog->time = now()->format('H:i:s');
                $solog->date = now()->format('Y-m-d');
                $solog->status = 'SO Created';
                $solog->created_by = Auth::id();
                $solog->so_id = $so->id;
                $solog->role = Auth::user()->selectedRole;
                $solog->save();
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
                        $saleperson = User::find($quotation->created_by);
                        $empProfile = EmployeeProfile::where('user_id', $quotation->created_by)->first(); 
                        return view('salesorder.update', compact('vehicles', 'quotationItems', 'quotation', 'calls', 'customerdetails','sodetails', 'soitems', 'empProfile', 'saleperson'));  
        }
        public function storesalesorderupdate(Request $request, $quotationId)
{
    // Validate and retrieve the Sales Order ID
    $so_id = $request->input('so_id');
    $so = So::findOrFail($so_id);
    // Update the Sales Order fields
    $so_number = $request->input('so_number'); // Get the input value
    $so->so_number = 'SO-' . $so_number; 
    $so->so_date = $request->input('so_date');
    $so->notes = $request->input('notes');
    $so->total = $request->input('total_payment');
    $so->receiving = $request->input('receiving_payment');
    $so->paidinso = $request->input('payment_so');
    $so->paidinperforma = $request->input('advance_payment_performa');
    $so->updated_by = auth()->id(); // or $request->user()->id
    $so->updated_at = Carbon::now();
    $so->save();

    // Delete existing Soitems records related to the Sales Order ID
    Soitems::where('so_id', $so->id)->delete();
    Vehicles::where('so_id', $so->id)->update(['so_id' => null]);
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
    $solog = New Solog();
    $solog->time = now()->format('H:i:s');
    $solog->date = now()->format('Y-m-d');
    $solog->status = 'SO Updated';
    $solog->created_by = Auth::id();
    $solog->so_id = $so->id;
    $solog->role = Auth::user()->selectedRole;
    $solog->save();
    return redirect()->route('dailyleads.index')->with('success', 'Sales Order updated successfully.');
}
public function cancel($id)
{
    info($id);
    $quotation = Quotation::where('calls_id', $id)->first();
    $calls = Calls::find($id);
    $calls->status = 'Quoted';
    $calls->save();
    $leadclosed = Closed::where('call_id', $id)->first();
    if($leadclosed)
    {
    $leadclosed->delete();
    }
    $so = So::where('quotation_id', $quotation->id)->first();
    $soitems = Soitems::where('so_id', $so->id)->get();
    foreach ($soitems as $soitem) {
        $vehicle = Vehicles::find($soitem->vehicles_id);
        if ($vehicle) {
            $vehicle->so_id = null;
            $vehicle->reservation_start_date = null;
            $vehicle->reservation_end_date = null;
            $vehicle->booking_person_id = null;
            $vehicle->save();
        }
    }
    foreach ($soitems as $soitem) {
        $soitem->delete();
    }
    $bookingrequest = BookingRequest::where('calls_id', $id)->first();
    if ($bookingrequest) {
        $bookingrequest->status = 'Rejected';
        $bookingrequest->save();
    }
    $solog = New Solog();
    $solog->time = now()->format('H:i:s');
    $solog->date = now()->format('Y-m-d');
    $solog->status = 'SO Cancel';
    $solog->created_by = Auth::id();
    $solog->so_id = $so->id;
    $solog->role = Auth::user()->selectedRole;
    $solog->save();
    $so->delete();
    return redirect()->back()->with('success', 'Sales Order and related items canceled successfully.');
}
public function showSalesSummary($sales_person_id, $count_type)
{
    $salesperson = DB::table('users')->where('id', $sales_person_id)->first();
    switch ($count_type) {
        case 'Pending Leads':
        $leadsQuery = DB::table('calls')
        ->select([
            'calls.id',
            'calls.created_at',
            'calls.name',
            'calls.custom_brand_model',
            'calls.phone',
            'calls.email',
            'calls.remarks',
            'calls.type',
            'calls.location',
            'calls.language',
            'calls.priority',
            'master_model_lines.model_line',
            'users.name as created_by',
            'brands.brand_name'
        ])
        ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
        ->leftJoin('users', 'calls.created_by', '=', 'users.id')
        ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls.sales_person', $sales_person_id);
        $leadsQuery->where('calls.status', 'New');
        break;
        case 'Bulk Deals':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->where('calls.sales_person', $sales_person_id);
            $leadsQuery->whereIn('calls.leadtype', ['Bulk Deals', 'Special Orders']);
            break;
        case 'Prospecting':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'prospectings.medium',
                'prospectings.time',
                'prospectings.date',
                'prospectings.salesnotes',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id')
            ->where('calls.sales_person', $sales_person_id)
            ->where('calls.status', 'Prospecting')
            ->whereDate('calls.created_at', '>=', '2023-10-01')
            ->groupby('calls.id');
            break;
            case 'Follow Up':
                $leadsQuery = DB::table('calls')
                ->select([
                    'calls.id',
                    'calls.created_at',
                    'calls.name',
                    'calls.custom_brand_model',
                    'calls.phone',
                    'calls.email',
                    'calls.remarks',
                    'calls.type',
                    'calls.location',
                    'calls.language',
                    'calls.priority',
                    'master_model_lines.model_line',
                    'fellow_up.method',
                    'fellow_up.time',
                    'fellow_up.date',
                    'fellow_up.sales_notes',
                    'users.name as created_by',
                    'brands.brand_name'
                ])
                ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                ->leftJoin('users', 'calls.created_by', '=', 'users.id')
                ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->leftJoin('fellow_up', 'calls.id', '=', 'fellow_up.calls_id')
                ->where('calls.sales_person', $sales_person_id)
                ->where('calls.status', 'Follow Up')
                ->whereDate('calls.created_at', '>=', '2023-10-01')
                ->groupby('calls.id');
                break;
        case 'Quoted':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'quotations.deal_value',
                'quotations.date',
                'quotations.sales_notes',
                'quotations.file_path',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id')
            ->where('calls.sales_person', $sales_person_id)
            ->where('calls.status', 'Quoted')
            ->whereDate('calls.created_at', '>=', '2023-10-01')
            ->groupby('calls.id');
            break;
        case 'Rejected':
            $leadsQuery = DB::table('calls')
            ->select([
                'calls.id',
                'calls.created_at',
                'calls.name',
                'calls.custom_brand_model',
                'calls.phone',
                'calls.email',
                'calls.remarks',
                'calls.type',
                'calls.location',
                'calls.language',
                'calls.priority',
                'master_model_lines.model_line',
                'lead_rejection.Reason',
                'lead_rejection.date',
                'lead_rejection.sales_notes',
                'users.name as created_by',
                'brands.brand_name'
            ])
            ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->leftJoin('lead_rejection', 'calls.id', '=', 'lead_rejection.call_id')
            ->where('calls.sales_person', $sales_person_id)
            ->where('calls.status', 'Rejected')
            ->whereDate('calls.created_at', '>=', '2023-10-01')
            ->groupby('calls.id');
            break;
        case 'Sales Order':
            $leadsQuery = DB::table('so')
            ->select([
                'calls.name as customername',
                'calls.email',
                'calls.phone',
                'quotations.created_at',
                'quotations.deal_value',
                'quotations.sales_notes',
                'quotations.file_path',
                'users.name',
                'so.so_number',
                'so.so_date',
                'quotations.calls_id',
                'users.name as created_by',
            ])
            ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
            ->leftJoin('users', 'quotations.created_by', '=', 'users.id')
            ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
            ->leftJoin('users', 'calls.created_by', '=', 'users.id')
            ->where('so.sales_person_id', $sales_person_id)
            ->groupby('so.id');
            break;
        default:
            // If count_type doesn't match any case, return an empty response
            return response()->json(['error' => 'Invalid count type'], 400);
    }
    // Return the JSON data for DataTables
    if (request()->ajax()) {
        return DataTables::of($leadsQuery)->make(true);
    }
    // Render the view
    return view('dailyleads.leadssummary', [
        'salesperson' => $salesperson,
        'countType' => $count_type,
    ]);
}
public function showSalespersonCommissions($sales_person_id, Request $request)
{
    $usdToAedRate = 3.67;
    $selectedMonth = $request->get('month') ?? now()->format('Y-m'); // Get the selected month, default to current
    // Fetch the salesperson's name
    $salesPerson = DB::table('users')->where('id', $sales_person_id)->select('name')->first();
    // Filter commissions for the selected salesperson and month
    $commissions = DB::table('vehicle_invoice')
        ->join('vehicle_invoice_items', 'vehicle_invoice.id', '=', 'vehicle_invoice_items.vehicle_invoice_id')
        ->join('so', 'vehicle_invoice.so_id', '=', 'so.id')
        ->join('users', 'so.sales_person_id', '=', 'users.id')
        ->leftJoin('vehicle_netsuite_cost', 'vehicle_invoice_items.vehicles_id', '=', 'vehicle_netsuite_cost.vehicles_id')
        ->where('so.sales_person_id', '=', $sales_person_id)
        ->where(DB::raw("DATE_FORMAT(vehicle_invoice.created_at, '%Y-%m')"), '=', $selectedMonth) // Filter by selected month
        ->select(
            'vehicle_invoice.invoice_number',
            'vehicle_invoice.id as invoice_id',
            'users.name',
            DB::raw('COUNT(vehicle_invoice_items.id) as total_invoice_items'),
            DB::raw('SUM(vehicle_netsuite_cost.cost) as total_vehicle_cost'),
            DB::raw("SUM(CASE WHEN vehicle_invoice.currency = 'USD' THEN vehicle_invoice_items.rate * $usdToAedRate ELSE vehicle_invoice_items.rate END) as total_rate_in_aed"),
            DB::raw('GROUP_CONCAT(vehicle_invoice_items.vehicles_id) as all_vehicles_ids'),
            'vehicle_invoice.currency'
        )
        ->groupBy('vehicle_invoice.id', 'users.name')
        ->get();
    // Calculate commission rates for the selected salesperson
    foreach ($commissions as $item) {
        $totalSales = $item->total_rate_in_aed;
        $commissionSlot = DB::table('commission_slots')
            ->where('min_sales', '<=', $totalSales)
            ->where(function($query) use ($totalSales) {
                $query->where('max_sales', '>=', $totalSales)
                      ->orWhereNull('max_sales');
            })
            ->orderBy('min_sales', 'desc')
            ->first();
        $item->commission_rate = $commissionSlot ? $commissionSlot->rate : 0;
    }
    // Return the view for this salesperson's commission
    return view('salesorder.commission', compact('commissions', 'selectedMonth', 'sales_person_id', 'salesPerson'));
}
    public function showVehicles($vehicle_invoice_id)
    {
    $usdToAedRate = 3.67;
    $invoiceData = DB::table('vehicle_invoice')
    ->join('so', 'vehicle_invoice.so_id', '=', 'so.id')
    ->join('clients', 'vehicle_invoice.clients_id', '=', 'clients.id')
    ->join('users', 'so.sales_person_id', '=', 'users.id')
    ->leftJoin('master_shipping_ports as pol_ports', 'vehicle_invoice.pol', '=', 'pol_ports.id') // Left join for POL
    ->leftJoin('master_shipping_ports as pod_ports', 'vehicle_invoice.pod', '=', 'pod_ports.id') // Left join for POD
    ->where('vehicle_invoice.id', $vehicle_invoice_id)
    ->select(
        'vehicle_invoice.invoice_number',
        'users.name as sales_person_name',
        'pol_ports.name as pol_name', // Port of Loading name, nullable
        'clients.name as customername',
        'clients.phone as customerphone',
        'clients.email as customeremail',
        'pod_ports.name as pod_name'  // Port of Discharge name, nullable
    )
    ->first();
    $vehicles = DB::table('vehicle_invoice_items')
        ->join('vehicle_invoice', 'vehicle_invoice.id', '=', 'vehicle_invoice_items.vehicle_invoice_id')
        ->join('vehicles', 'vehicle_invoice_items.vehicles_id', '=', 'vehicles.id')
        ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
        ->join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->leftJoin('vehicle_netsuite_cost', 'vehicles.id', '=', 'vehicle_netsuite_cost.vehicles_id')
        ->where('vehicle_invoice.id', '=', $vehicle_invoice_id)
        ->select(
            'vehicles.vin',
            'brands.brand_name',
            'master_model_lines.model_line',
            'varaints.name as variant_name',
            DB::raw('SUM(vehicle_netsuite_cost.cost) as total_vehicle_cost'),
            DB::raw("SUM(CASE WHEN vehicle_invoice.currency = 'USD' THEN vehicle_invoice_items.rate * $usdToAedRate ELSE vehicle_invoice_items.rate END) as total_rate_in_aed"),
            'vehicle_invoice.currency'
        )
        ->groupBy('vehicles.id', 'brands.brand_name', 'master_model_lines.model_line', 'varaints.name', 'vehicles.vin')
        ->get();
    foreach ($vehicles as $item) {
        $gross = $item->total_rate_in_aed - $item->total_vehicle_cost;
        if ($gross <= 0) {
            $item->commission_rate = 0;
            continue;
        }
        $totalSales = $item->total_rate_in_aed;
        $commissionSlot = DB::table('commission_slots')
            ->where('min_sales', '<=', $totalSales)
            ->where(function ($query) use ($totalSales) {
                $query->where('max_sales', '>=', $totalSales)
                    ->orWhereNull('max_sales');
            })
            ->orderBy('min_sales', 'desc')
            ->first();
        $item->commission_rate = $commissionSlot ? $commissionSlot->rate : 0;
    }
    return view('salesorder.vehicles', [
        'vehicles' => $vehicles,
        'salesPerson' => $invoiceData->sales_person_name,
        'pol' => $invoiceData->pol_name,
        'pod' => $invoiceData->pod_name,
        'customername' => $invoiceData->customername,
        'customerphone' => $invoiceData->customerphone,
        'customeremail' => $invoiceData->customeremail,
        'invoice_number' => $invoiceData->invoice_number
    ]);    
    }
        }
