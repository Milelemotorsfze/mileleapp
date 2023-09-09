<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Brand;
use App\Events\DataUpdatedEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\BookingRequest;
use App\Models\ColorCode;
use App\Models\MasterModelLines;
use App\Models\Varaint;
use App\Models\Vehicles;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create($call_id)
{
    $brands = Brand::all();
    return view('booking.create', compact('call_id', 'brands'));
}
    public function getModelLines(Request $request, $brandId)
    {
        $modelLines = MasterModelLines::where('brand_id', $brandId)->pluck('model_line', 'id');
        return response()->json($modelLines);
    }

    public function getVariants(Request $request, $modelLineId)
    {
        $variants = Varaint::where('master_model_lines_id', $modelLineId)->pluck('name', 'id');
        return response()->json($variants);
    }
    public function getInteriorColors(Request $request, $variantId)
{
    $colors = ColorCode::where('belong_to', 'int')->pluck('name', 'id');
    return response()->json($colors);
}

public function getExteriorColors(Request $request, $variantId)
{
    $colors = ColorCode::where('belong_to', 'ex')->pluck('name', 'id');
    return response()->json($colors);
}
public function getbookingvehicles($variantId, $interiorColorId = null, $exteriorColorId = null)
{
    $query = Vehicles::select([
        'vehicles.vin as vin',
        'vehicles.id',
        'brands.brand_name as brand',
        'master_model_lines.model_line',
        'varaints.name as variant_name',
        'varaints.detail as variant_detail',
        'interior_color_code.name as interior_color',
        'exterior_color_code.name as exterior_color'
    ])
    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->leftJoin('color_codes as interior_color_code', 'vehicles.int_colour', '=', 'interior_color_code.id')
    ->leftJoin('color_codes as exterior_color_code', 'vehicles.ex_colour', '=', 'exterior_color_code.id')
    ->where('vehicles.varaints_id', $variantId);
    if ($interiorColorId !== null) {
        $query->where('int_colour', $interiorColorId);
    }
    if ($exteriorColorId !== null) {
        $query->where('ex_colour', $exteriorColorId);
    }
    $query->whereNotNull('vehicles.vin');
    $availableVehicles = $query->get();
    return response()->json($availableVehicles);
}
public function store(Request $request)
    {
        // Extract data from the request
        $date = $request->input('date');
        $callId = $request->input('call_id');
        $selectedData = json_decode($request->input('selectedData'), true);
        foreach ($selectedData as $item) {
            $vehicleId = $item['vehicleId']; // Check if $item['vehicleId'] is correct
    $days = $item['days']; // Check if $item['days'] is correct

    // Output the values for debugging
    info('Vehicle ID: ' . $vehicleId);
    info('Days: ' . $days);
            BookingRequest::create([
                'date' => $date,
                'status' => "New",
                'calls_id' => $callId,
                'created_by' => Auth::id(),
                'vehicle_id' => $vehicleId,
                'days' => $days,
            ]);
        }
        // Optionally, you can return a response indicating success
        return response()->json(['message' => 'Booking request submitted successfully']);
    }
    public function index(Request $request)
{
    if ($request->ajax()) {
        $status = $request->input('status');
        if($status === "New")
        {
        $data = BookingRequest::select([
                'booking_requests.id',
                'booking_requests.calls_id',
                DB::raw("DATE_FORMAT(booking_requests.date, '%d-%b-%Y') as date"),
                'booking_requests.days',
                'vehicles.vin',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'vehicles.int_colour',
                'vehicles.ex_colour',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('vehicles', 'booking_requests.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking_requests.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status)
            ->groupBy('booking_requests.id');
        }
        else if($status === "Approved Without SO") {
            $status = "Approved";
            $data = Booking::select([
                'booking.id',
                'booking.booking_start_date',
                'booking.booking_end_date',
                'booking.calls_id',
                'vehicles.vin',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'vehicles.int_colour',
                'vehicles.ex_colour',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('booking_requests', 'booking.booking_requests_id', '=', 'booking_requests.id')
            ->leftJoin('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status)
            ->whereDate('booking.booking_end_date', '>=', now())
            ->groupBy('booking.id'); 
        }
        else if($status === "Approved With SO") {
            $status = "Approved";
            $data = Booking::select([
                'booking.id',
                'booking.booking_start_date',
                'booking.booking_end_date',
                'booking.calls_id',
                'vehicles.vin',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'vehicles.int_colour',
                'vehicles.ex_colour',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('booking_requests', 'booking.booking_requests_id', '=', 'booking_requests.id')
            ->leftJoin('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status)
            ->whereDate('booking.booking_end_date', '>=', now())
            ->whereNotNull('vehicles.so_id')
            ->groupBy('booking.id'); 
        }
        else if($status === "Expire") {
            $status = "Approved";
            $data = Booking::select([
                'booking.id',
                'booking.booking_start_date',
                'booking.booking_end_date',
                'booking.calls_id',
                'vehicles.vin',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'vehicles.int_colour',
                'vehicles.ex_colour',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('booking_requests', 'booking.booking_requests_id', '=', 'booking_requests.id')
            ->leftJoin('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status)
            ->whereDate('booking.booking_end_date', '<', now())
            ->groupBy('booking.id'); 
        }
        else if($status === "Rejected") {
            $status = "Rejected";
            $data = BookingRequest::select([
                'booking_requests.id',
                'booking_requests.calls_id',
                DB::raw("DATE_FORMAT(booking_requests.date, '%d-%b-%Y') as date"),
                'booking_requests.days',
                'vehicles.vin',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'vehicles.int_colour',
                'vehicles.ex_colour',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('vehicles', 'booking_requests.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking_requests.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status)
            ->groupBy('booking_requests.id');
        }
            return DataTables::of($data)
            ->addColumn('created_by', function ($row) {
                return User::find($row->created_by)->name ?? '';
            })
            ->addColumn('vin', function ($row) {
                return $row->vin ?? '';
            })
            ->addColumn('so_number', function ($row) {
                return $row->so_number ?? '';
            })
            ->addColumn('interior_color', function ($row) {
                $colorCode = ColorCode::find($row->int_colour);
                return $colorCode ? $colorCode->name : '';
            })
            ->addColumn('exterior_color', function ($row) {
                $colorCode = ColorCode::find($row->ex_colour);
                return $colorCode ? $colorCode->name : '';
            })
            ->addColumn('action', function ($row) {
            })
            ->toJson();
    }

    return view('booking.index');
}
public function approval(Request $request)
    {
        $id = $request->input('id');
        $days = $request->input('days');
        $status = $request->input('status');
        $bookingRequest = BookingRequest::find($id);
        $today = now();
        if($status === "Approved"){
        $vehicle_id = $bookingRequest->vehicle_id;
        $calls_id = $bookingRequest->calls_id;
        $created_by = $bookingRequest->created_by;
        $vehicle = vehicles::find($vehicle_id);
        $booking_end_date = clone $today;
        $booking_end_date->addDays($days);      
        Booking::create([
            'vehicle_id' => $vehicle_id,
            'calls_id' => $calls_id,
            'created_by' => $created_by,
            'date' => $today,
            'booking_start_date' => $today,
            'booking_end_date' => $booking_end_date,
            'booking_requests_id' => $id,
        ]);
        $vehicle->update([
            'reservation_start_date' => $today,
            'reservation_end_date' => $booking_end_date,
        ]);
        $vehicle->save();
        event(new DataUpdatedEvent(['id' => $vehicle_id, 'message' => "Data Update"]));
        }
        $bookingRequest->update([
            'status' => $status,
            'days' => $days,
            'process_date' => $today,
            'process_by' => Auth::id(),
        ]);
        $bookingRequest->save();
    return response()->json(['message' => 'Booking Status Update successfully'], 200);
    }
}
