<?php

namespace App\Http\Controllers;

use App\Models\PurchasingOrder;
use App\Models\PurchasingOrderItems;
use Illuminate\Http\Request;
use App\Models\Varaint;
use App\Models\Supplier;
use App\Models\Vehicles;
use App\Models\Movement;
use App\Models\Vendor;
use App\Models\PaymentLog;
use App\Models\User;
use App\Models\Vehicleslog;
use Carbon\Carbon;
use App\Models\ModelHasRoles;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonTimeZone;
use App\Models\Purchasinglog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
    public function filter($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')->where('status', $status)->get();
        return view('warehouse.index', compact('data'));
    }    
    public function filterpayment($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Initiated')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    
        return view('warehouse.index', compact('data'));
    }
    public function filterpaymentrel($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Release Requested')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    
        return view('warehouse.index', compact('data'));
    }
    public function filterintentreq($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Request for Payment')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    
        return view('warehouse.index', compact('data'));
    }
    public function filterpendingrelease($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Initiate Approved')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    
        return view('warehouse.index', compact('data'));
    }
    public function filterpendingdebits($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Release Approved')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    
        return view('warehouse.index', compact('data'));
    }
    public function filterpendingfellow($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Completed')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    
        return view('warehouse.index', compact('data'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $vendors = Vendor::where('category', 'vehicle-procurment')->get();
    $variants = Varaint::all();
    join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();

    return view('warehouse.create', compact('variants', 'vendors'));
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
        $vendors_id = $request->input('vendors_id');
        $purchasingOrder = new PurchasingOrder();
        $purchasingOrder->po_date = $poDate;
        $purchasingOrder->po_number = $poNumber;
        $purchasingOrder->vendors_id = $vendors_id;
        $purchasingOrder->status = "Pending Approval";
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
        $estimated_arrival = $request->input('estimated_arrival');
        $territory = $request->input('territory');
        $count = count($variantNames);
        foreach ($variantNames as $key => $variantName) {
        if ($variantName === null && $key === $count - 1) {
        continue;
        }
        $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
        $vin = $vins[$key];
        $ex_colour = $ex_colours[$key];
        $int_colour = $int_colours[$key];
        $estimation_arrival = $estimated_arrival[$key];
        $territorys = $territory[$key];
        $vehicle = new Vehicles();
        $vehicle->varaints_id = $variantId;
        $vehicle->vin = $vin;
        $vehicle->ex_colour = $ex_colour;
        $vehicle->int_colour = $int_colour;
        $vehicle->estimation_date = $estimation_arrival;
        $vehicle->territory = $territorys;
        $vehicle->purchasing_order_id = $purchasingOrderId;
        $vehicle->status = "Not Approved";
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $purchasinglog = new Purchasinglog();
        $purchasinglog->time = now()->toTimeString();
        $purchasinglog->date = now()->toDateString();
        $purchasinglog->status = 'PO Created';
        $purchasinglog->purchasing_order_id = $purchasingOrderId;
        $purchasinglog->variant = $variantId;
        $purchasinglog->estimation_date = $estimation_arrival;
        $purchasinglog->territory = $territorys;
        $purchasinglog->ex_colour = $ex_colour;
        $purchasinglog->int_colour = $int_colour;
        $purchasinglog->created_by = auth()->user()->id;
        $purchasinglog->role = Auth::user()->selectedRole;
        $purchasinglog->save();
    }
    }
    return redirect()->route('purchasing-order.index')->with('success', 'PO Created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
    $vendorsname = Vendor::where('id', $purchasingOrder->vendors_id)->value('trade_name_or_individual_name');
    $vehicleslog = Vehicleslog::whereIn('vehicles_id', $vehicles->pluck('id'))->get();
    $purchasinglog = Purchasinglog::where('purchasing_order_id', $id)->get();
        $previousId = PurchasingOrder::where('id', '<', $id)->max('id');
        $nextId = PurchasingOrder::where('id', '>', $id)->min('id');
        return view('purchase.show', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ], compact('purchasingOrder', 'variants', 'vehicles', 'vendorsname', 'vehicleslog','purchasinglog'));
    }

    public function edit($id)
{
    $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
    $vendorsname = Vendor::where('id', $purchasingOrder->vendors_id)->value('trade_name_or_individual_name');
    return view('warehouse.edit', compact('purchasingOrder', 'variants', 'vehicles', 'vendorsname'));
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
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
        $estimated_arrival = $request->input('estimated_arrival');
        $territory = $request->input('territory');
        $count = count($variantNames);
        foreach ($variantNames as $key => $variantName) {
        if ($variantName === null && $key === $count - 1) {
        continue;
        }
        $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
        $ex_colour = $ex_colours[$key];
        $int_colour = $int_colours[$key];
        $estimated_arrivals = $estimated_arrival[$key];
        $territorys = $territory[$key];
        $vin = $vins[$key];
        $vehicle = new Vehicles();
        $vehicle->varaints_id = $variantId;
        $vehicle->vin = $vin;
        $vehicle->ex_colour = $ex_colour;
        $vehicle->int_colour = $int_colour;
        $vehicle->estimation_date = $estimated_arrivals;
        $vehicle->territory = $territorys;
        $vehicle->purchasing_order_id = $purchasingOrderId;
        $vehicle->status = "New Vehicles";
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $purchasinglog = new Purchasinglog();
        $purchasinglog->time = now()->toTimeString();
        $purchasinglog->date = now()->toDateString();
        $purchasinglog->status = 'Adding New Vehicle';
        $purchasinglog->purchasing_order_id = $purchasingOrderId;
        $purchasinglog->variant = $variantId;
        $purchasinglog->estimation_date = $estimated_arrivals;
        $purchasinglog->territory = $territorys;
        $purchasinglog->ex_colour = $ex_colour;
        $purchasinglog->int_colour = $int_colour;
        $purchasinglog->created_by = auth()->user()->id;
        $purchasinglog->role = Auth::user()->selectedRole;
        $purchasinglog->save();
    }
                $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
                if ($purchasingOrder) {
                    $purchasingOrder->status = 'Pending Approval';
                    $purchasingOrder->save();
                     }
    }
    return back()->with('success', 'Add More Vehicles In PO successful!');
    }
    public function deletes($id)
{
    // Delete related records from vehicles_log table
    $vehicleIds = Vehicles::where('purchasing_order_id', $id)->pluck('id');
    Vehicleslog::whereIn('vehicles_id', $vehicleIds)->delete();

    // Delete records from other related tables
    PurchasingOrderItems::where('purchasing_order_id', $id)->delete();
    Vehicles::where('purchasing_order_id', $id)->delete();
    Purchasinglog::where('purchasing_order_id', $id)->delete();

    // Delete the purchasing order itself
    PurchasingOrder::where('id', $id)->delete();

    return back()->with('success', 'Deletion successful');
    $notPaidCount = Vehicles::where('purchasing_order_id', $id)
        ->where('payment_status', 'Paid')
        ->count();

    if ($notPaidCount > 0) {
        return back()->with('error', 'Cannot delete. Some vehicles have payment status is "Paid"');
    } else {
        // Delete purchasing order items
        PurchasingOrderItems::where('purchasing_order_id', $id)->delete();

        // Delete vehicles
        Vehicles::where('purchasing_order_id', $id)->delete();

        // Delete purchasing order
        $purchasingOrder = PurchasingOrder::find($id);
        $purchasingOrder->delete();

        return back()->with('success', 'Deletion successful');
    }
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

    public function viewdetails($id)
{
    $varaint = Varaint::get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $data = Vehicles::where('purchasing_order_id', $id)->where('status', '!=', 'cancel')->get();
    $vendorsname = Vendor::where('id', $purchasingOrder->vendors_id)->value('trade_name_or_individual_name');
    $sales_persons = ModelHasRoles::get();
    $sales_ids = $sales_persons->pluck('model_id');
    $sales = User::whereIn('id', $sales_ids)->get();
    return view('warehouse.vehiclesdetails', compact('purchasingOrder', 'varaint', 'data', 'vendorsname', 'sales'));
}
public function checkcreatevins(Request $request)
    {
        $vinValues = $request->input('vin');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->pluck('vin')->toArray();
        if (!empty($existingVins)) {
            return response()->json('duplicate');
        }
        return response()->json('unique');
    }
    public function checkeditcreate(Request $request)
    {
        $vinValues = $request->input('vin');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->pluck('vin')->toArray();
        if (!empty($existingVins)) {
            return response()->json('duplicate');
        }
        return response()->json('unique');
    }

    public function checkeditvins(Request $request)
    {
        $vinValues = $request->input('oldvin');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->pluck('vin')->toArray();
        if (!empty($existingVins)) {
            return response()->json('duplicate');
        }
        return response()->json('unique');
    }
    public function updatepurchasingData(Request $request)
{
    $updatedData = $request->json()->all();

    foreach ($updatedData as $data) {
        $vehicleId = $data['id'];
        $fieldName = $data['name'];
        $fieldValue = $data['value'];
        $vehicle = Vehicles::find($vehicleId);
        if ($vehicle) {
            $oldValues = $vehicle->getAttributes();
            $vehicle->setAttribute($fieldName, $fieldValue);
            $vehicle->save();
            $changes = [];
            foreach ($oldValues as $field => $oldValue) {
                if ($field !== 'created_at' && $field !== 'updated_at') {
                    $newValue = $vehicle->$field;
                    if ($oldValue != $newValue) {
                        $changes[$field] = [
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                        ];
                    }
                }
            }
            info($changes);
            if (!empty($changes)) {
                $vehicle->status = 'New Changes'; // Set the vehicle status
                $vehicle->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                foreach ($changes as $field => $change) {
                    $vehicleslog = new Vehicleslog();
                    $vehicleslog->time = $currentDateTime->toTimeString();
                    $vehicleslog->date = $currentDateTime->toDateString();
                    $vehicleslog->status = 'Update Vehicles On Purchased Order';
                    $vehicleslog->vehicles_id = $vehicleId;
                    $vehicleslog->field = $field;
                    $vehicleslog->old_value = $change['old_value'];
                    $vehicleslog->new_value = $change['new_value'];
                    $vehicleslog->created_by = auth()->user()->id;
                    $vehicleslog->role = Auth::user()->selectedRole;
                    $vehicleslog->save();
                }
                $purchasingOrderId = $vehicle->purchasing_order_id;
                $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
                if ($purchasingOrder) {
                    $purchasingOrder->status = 'Pending Approval';
                    $purchasingOrder->save();
                     }

            }
        }
    }
    return response()->json(['message' => 'Data updated successfully']);
}
public function purchasingupdateStatus(Request $request)
    {
        $id = $request->input('orderId');
        $status = $request->input('status');
        $purchasingOrder = PurchasingOrder::find($id);
        if (!$purchasingOrder) {
            return response()->json(['message' => 'Purchasing order not found'], 404);
        }
        $purchasingOrder->status = $status;
        $purchasingOrder->save();
        $vehicles = Vehicles::where('purchasing_order_id', $id)->where('status', '!=', 'Rejected')->get();
        foreach ($vehicles as $vehicle) {
            if ($vehicle->status == 'New Changes' || $vehicle->status == 'Not Approved') {
            $vehicle->status = 'Approved';
            $vehicle->save();
            $ex_colour = $vehicle->ex_colour;
            $int_colour = $vehicle->int_colour;
            $variantId = $vehicle->	varaints_id;
            $estimation_arrival = $vehicle->estimation_date;
            $territorys = $vehicle->territory;
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'PO Approved';
            $purchasinglog->purchasing_order_id = $id;
            $purchasinglog->variant = $variantId;
            $purchasinglog->estimation_date = $estimation_arrival;
            $purchasinglog->territory = $territorys;
            $purchasinglog->ex_colour = $ex_colour;
            $purchasinglog->int_colour = $int_colour;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->save();
        }
    }
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
    public function confirmPayment($id)
    {
        $vehicle = Vehicles::find($id);
        if ($vehicle) {
            $vehicle->status = 'Request for Payment';
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Request for Payment';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Payment";
                $vehicleslog->old_value = "Not Paid";
                $vehicleslog->new_value = "Request for Initiate Payment";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            return redirect()->back()->with('success', 'Payment confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function cancel($id)
    {
        $vehicle = Vehicles::findOrFail($id);
        if ($vehicle->status == 'Approved' || $vehicle->status == 'Request for Payment' || $vehicle->status == 'Payment In-Process') {
            $vehicle->status = 'Request for Cancel';
        }
        else
        {
        $vehicle->status = 'cancel';
        }
        $vehicle->save();
        return redirect()->back()->with('success', 'Vehicle cancellation request submitted successfully.');
    }
    public function rejecteds($id)
    {
        $vehicle = Vehicles::find($id);
        if ($vehicle) {
            $vehicle->status = 'Rejected';
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Rejected By BOD';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Rejected";
                $vehicleslog->old_value = "Pending Approval";
                $vehicleslog->new_value = "Rejected By BOD";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            return redirect()->back()->with('success', 'Payment confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function unrejecteds($id)
    {
        $vehicle = Vehicles::find($id);
        if ($vehicle) {
            $vehicle->status = 'Not Approved';
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Un-Rejected By BOD';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Un-Rejected";
                $vehicleslog->old_value = "Rejected By BOD";
                $vehicleslog->new_value = "Pending Approval";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            return redirect()->back()->with('success', 'Un-Reject confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function deleteVehicle($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehiclesLog = new Vehicleslog();
        $vehiclesLog->time = $currentDateTime->toTimeString();
        $vehiclesLog->date = $currentDateTime->toDateString();
        $vehiclesLog->status = 'Deleted By BOD';
        $vehiclesLog->vehicles_id = $vehicle->id;
        $vehiclesLog->field = "Status";
        $vehiclesLog->old_value = $vehicle->status;
        $vehiclesLog->new_value = "Deleted By BOD";
        $vehiclesLog->created_by = auth()->user()->id;
        $vehiclesLog->role = Auth::user()->selectedRole;
        $vehiclesLog->save();
        $vehicle->delete();
        return redirect()->back()->with('success', 'Vehicle deleted successfully.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentintconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Initiated';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Request for Initiate Payment";
            $vehicleslog->new_value = "Payment Initiated";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiate confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleaserejected($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Rejected';
        $vehicle->payment_status = 'Payment Initiate Rejected';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiate Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Payment Initiated";
            $vehicleslog->new_value = "Payment Initiate Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Payment Initiate Rejected confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleaseconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Initiate Approved';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiate Approved';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Payment Initiated";
            $vehicleslog->new_value = "Payment Initiate Approved";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Payment Initiate Approved confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Release Requested';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Release Requested';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Payment Initiated Approved";
            $vehicleslog->new_value = "Payment Release Requested";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Payment Release Requested confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleasesconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Release Approved';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Release Approved';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Payment Release Requested";
            $vehicleslog->new_value = "Payment Release Approved";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Payment Release Approved confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleasesrejected($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Rejected';
        $vehicle->payment_status = 'Payment Release Rejected';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Release Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Payment Initiated Approved";
            $vehicleslog->new_value = "Payment Release Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Payment Release Rejected confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirmdebited($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Completed';
        $vehicle->payment_status = 'Payment Completed';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Completed';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Amount Debited";
            $vehicleslog->new_value = "Payment Completed";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Payment Completed confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirmvendors($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Incoming Stock';
        $vehicle->payment_status = 'Vendor Confirmed';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Incoming Stock';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment";
            $vehicleslog->old_value = "Payment Completed";
            $vehicleslog->new_value = "Incoming Stock";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Incoming Stock confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function purchasingallupdateStatus(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    
    $vehicles = DB::table('vehicles')
        ->where('payment_status', 'Payment Initiated')
        ->where('purchasing_order_id', $id)
        ->get();
    foreach ($vehicles as $vehicle) {
    if ($status == 'Approved') {
            $paymentStatus = 'Payment Initiate Approved';
        } elseif ($status == 'Rejected') {
            $paymentStatus = 'Payment Initiate Rejected';
        }
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['payment_status' => $paymentStatus]);
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Payment Initiate Status';
        $vehicleslog->vehicles_id = $vehicle->id;
        $vehicleslog->field = 'Payment';
        $vehicleslog->old_value = 'Payment Initiated';
        $vehicleslog->new_value = $paymentStatus;
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
    }
    return redirect()->back()->with('success', 'Payment Amount Debited confirmed. Vehicle status updated.');
}
public function purchasingallupdateStatusrel(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    
    $vehicles = DB::table('vehicles')
        ->where('payment_status', 'Payment Release Requested')
        ->where('purchasing_order_id', $id)
        ->get();
    foreach ($vehicles as $vehicle) {
    if ($status == 'Approved') {
            $paymentStatus = 'Payment Release Approved';
        } elseif ($status == 'Rejected') {
            $paymentStatus = 'Payment Release Rejected';
        }
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['payment_status' => $paymentStatus]);
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Payment Release Status';
        $vehicleslog->vehicles_id = $vehicle->id;
        $vehicleslog->field = 'Payment';
        $vehicleslog->old_value = 'Payment Release Requested';
        $vehicleslog->new_value = $paymentStatus;
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
    }
    return redirect()->back()->with('success', 'Payment Status Updated');
}
}
