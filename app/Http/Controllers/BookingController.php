<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Brand;
use App\Events\DataUpdatedEvent;
use App\Models\User;
use App\Models\Closed;
use App\Models\So;
use App\Models\Calls;
use Illuminate\Support\Facades\DB;
use App\Models\BookingRequest;
use App\Models\BookingExtended;
use App\Models\ColorCode;
use App\Models\MasterModelLines;
use App\Models\Varaint;
use App\Models\Vehicles;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookingController extends Controller
{
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
    $today = now();
    $query = Vehicles::select([
        'vehicles.vin as vin',
        'vehicles.id',
        'brands.brand_name as brand',
        'master_model_lines.model_line',
        'varaints.name as variant_name',
        'varaints.detail as variant_detail',
        'interior_color_code.name as interior_color',
        'exterior_color_code.name as exterior_color',
        \DB::raw('CASE WHEN vehicles.grn_id IS NULL THEN "Incoming" ELSE "Arrived" END as grn_status')
    ])
    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->leftJoin('color_codes as interior_color_code', 'vehicles.int_colour', '=', 'interior_color_code.id')
    ->leftJoin('color_codes as exterior_color_code', 'vehicles.ex_colour', '=', 'exterior_color_code.id')
    ->where(function($query) use ($today) {
        $query->whereNull('reservation_end_date')
            ->orWhere('reservation_end_date', '<', $today);
    })
    ->where('vehicles.varaints_id', $variantId);
    if ($interiorColorId !== null) {
        $query->where('int_colour', $interiorColorId);
    }
    if ($exteriorColorId !== null) {
        $query->where('ex_colour', $exteriorColorId);
    }
    $query->whereNotNull('vehicles.vin');
    $query->whereNull('vehicles.so_id');
    $query->whereNull('vehicles.gdn_id');
    $availableVehicles = $query->get();
    return response()->json($availableVehicles);
}
public function store(Request $request)
    {
        $date = $request->input('date');
        $callId = $request->input('call_id');
        $selectedData = json_decode($request->input('selectedData'), true);
        foreach ($selectedData as $item) {
            $vehicleId = $item['vehicleId'];
    $days = $item['days'];
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
        return response()->json(['message' => 'Booking request submitted successfully']);
    }
    public function index(Request $request)
{
    $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
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
            ->where('booking_requests.status', $status);
            if ($hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            $data = $data->groupBy('booking_requests.id');
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
            ->whereDate('booking.booking_end_date', '>=', now());
            if ($hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            $data = $data->groupBy('booking.id');
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
            ->whereNotNull('vehicles.so_id');
            if ($hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            $data = $data->groupBy('booking.id');
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
            ->whereDate('booking.booking_end_date', '<', now());
            if ($hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            $data = $data->groupBy('booking.id');
        }
        else if($status === "Rejected") {
            $status = "Rejected";
            $data = BookingRequest::select([
                'booking_requests.id',
                'booking_requests.calls_id',
                DB::raw("DATE_FORMAT(booking_requests.date, '%d-%b-%Y') as date"),
                'booking_requests.days',
                'booking_requests.reason',
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
            ->where('booking_requests.status', $status);
            if ($hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            $data = $data->groupBy('booking_requests.id');
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
        $reason = $request->input('reason');
        $bookingRequest = BookingRequest::find($id);
        $today = now();
        if($status === "Approved"){
        $vehicle_id = $bookingRequest->vehicle_id;
        $calls_id = $bookingRequest->calls_id;
        $created_by = $bookingRequest->created_by;
        $vehicle = vehicles::find($vehicle_id);
        $existingBooking = Booking::where('vehicle_id', $vehicle_id)
        ->where('booking_end_date', '>', $today)
        ->first();
        if ($existingBooking) {
        return response()->json(['error' => 'Another booking for the same vehicle already exists.'], 400);
        }
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
        $closedRow = Closed::where('call_id', $calls_id)->first();
        if ($closedRow) {
            $so_id = $closedRow->so_id;
            $vehicle->forceFill(['so_id' => $so_id])->save();
        }
        event(new DataUpdatedEvent(['id' => $vehicle_id, 'message' => "Data Update"]));
        }
        $bookingRequest->update([
            'status' => $status,
            'days' => $days,
            'reason' => $reason,
            'process_date' => $today,
            'process_by' => Auth::id(),
        ]);
        $bookingRequest->save();
    return response()->json(['message' => 'Booking Status Update successfully'], 200);
    }
    public function checkingso(Request $request) {
        $callId = $request->input('call_id');
        info($callId);
        $rowExists = Closed::where('call_id', $callId)->exists();
        if ($rowExists) {
            $closedRow = Closed::where('call_id', $callId)->first();
            $soIdExists = !empty($closedRow->so_id);
            $isEditable = $soIdExists;
        } else {
            $isEditable = false;
        }
        return response()->json(['editable' => $isEditable]);
    }
    public function extended(Request $request)
    {
        $id = $request->input('id');
        $days = $request->input('days');
        info($id);
        $reason = $request->input('reason');
        BookingExtended::create([
            'booking_id' => $id,
            'days' => $days,
            'reason' => $reason,
        ]);
        $booking = Booking::where('id', $id)->first();
        if ($booking) {
            $booking_end_date = date('Y-m-d H:i:s', strtotime($booking->booking_end_date . ' + ' . $days . ' days'));
            $vehicle_id = $booking->vehicle_id;
            $vehicle = vehicles::find($vehicle_id);
            $vehicle->update([
                'reservation_end_date' => $booking_end_date,
            ]);
            $vehicle->save();
            $booking->booking_end_date = $booking_end_date;
            $booking->save();
        }
        return response()->json(['message' => 'Booking Status Update successfully'], 200);
    }       
}
