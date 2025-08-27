<?php

namespace App\Http\Controllers;
use App\Models\Booking;
use App\Models\UserActivities;
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
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\QuotationItem;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function create($call_id)
{
    $useractivities = new UserActivities();
    $useractivities->activity = "Create Booking";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    $quotations = Quotation::where('calls_id', $call_id)->get();
    $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
    $interiorColours = ColorCode::where('belong_to', 'int')->get();
    $variants = [];
    $mastermodellines = [];
    $variantsMasterModel = [];
    foreach ($quotations as $quotation) {
        $masterModelLineItems = QuotationItem::where("reference_type", 'App\Models\MasterModelLines')
            ->where('quotation_id', $quotation->id)
            ->where('is_addon', false)->get();
        foreach ($masterModelLineItems as $masterModelLineItem) {
            $masterModelLine = MasterModelLines::find($masterModelLineItem->reference_id);
            $variantsFromMasterModelLine = Varaint::where('master_model_lines_id', $masterModelLine->id)->get();

            foreach ($variantsFromMasterModelLine as $variantFromMasterModelLine) {
                $variants[$variantFromMasterModelLine->id] = $variantFromMasterModelLine->name;
                $mastermodellines[$variantFromMasterModelLine->master_model_lines_id] = $masterModelLine->model_line;
                $variantsMasterModel[$variantFromMasterModelLine->id] = $masterModelLine->id;
            }
        }
        $variantItems = QuotationItem::where("reference_type", 'App\Models\Varaint')
            ->where('quotation_id', $quotation->id)->get();

        foreach ($variantItems as $variantItem) {
            $variant = Varaint::with('master_model_lines')->find($variantItem->reference_id);

            if ($variant && $variant->master_model_lines_id) {
                $variants[$variant->id] = $variant->name;
                $mastermodellines[$variant->master_model_lines->id] = $variant->master_model_lines->model_line;
                $variantsMasterModel[$variant->id] = $variant->master_model_lines->id;
            }
        }
    }
    return view('booking.create', compact('call_id', 'variants', 'mastermodellines', 'variantsMasterModel', 'exteriorColours', 'interiorColours'));
}
    public function getModelLines(Request $request, $brandId)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Get the Model into booking section";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $modelLines = MasterModelLines::where('brand_id', $brandId)->pluck('model_line', 'id');
        return response()->json($modelLines);
    }

    public function getVariants(Request $request, $modelLineId)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Get the Variant into Booking Section";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $variants = Varaint::where('master_model_lines_id', $modelLineId)->pluck('name', 'id');
        return response()->json($variants);
    }
    public function getInteriorColors(Request $request, $variantId)
{
    $useractivities =  New UserActivities();
    $useractivities->activity = "Get the Colour interior Booking Section";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    $colors = ColorCode::where('belong_to', 'int')->pluck('name', 'id');
    return response()->json($colors);
}
public function getExteriorColors(Request $request, $variantId)
{
    $useractivities =  New UserActivities();
    $useractivities->activity = "Get the Exterior Colour Into Booking Sector";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    $colors = ColorCode::where('belong_to', 'ex')->pluck('name', 'id');
    return response()->json($colors);
}
public function getbookingvehicles($variantId, $interiorColorId = null, $exteriorColorId = null)
{
    $useractivities =  New UserActivities();
    $useractivities->activity = "Shifting the Vehicle into Booking List";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    $today = now();
    $query = Vehicles::select([
        'vehicles.vin as vin',
        'vehicles.price as price',
        'vehicles.id',
        'brands.brand_name as brand',
        'master_model_lines.model_line',
        'varaints.name as variant_name',
        'varaints.detail as variant_detail',
        'model_detail as model_detail',
        'interior_color_code.name as interior_color',
        'exterior_color_code.name as exterior_color',
        \DB::raw('CASE WHEN vehicles.movement_grn_id IS NULL THEN "Incoming" ELSE "Arrived" END as grn_status')
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
    $query->groupBy('varaints.id', 'interior_color_code.name', 'exterior_color_code.name');
    $availableVehicles = $query->get();
    return response()->json($availableVehicles);
}
public function store(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Store the Booking Vehicle";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $date = $request->input('date');
        $callId = $request->input('call_id');
        $bookingnotes = $request->input('bookingnotes');
        $etd = $request->input('etd');
        $selectedData = json_decode($request->input('selectedData'), true);
        foreach ($selectedData as $item) {
            $vehicleId = $item['vehicleId'] ?? null;
            $days = $item['days'] ?? null;
            
            // Skip if required data is missing
            if (!$vehicleId) {
                continue;
            }
            
            BookingRequest::create([
                'date' => $date,
                'status' => "New",
                'calls_id' => $callId,
                'bookingnotes' => $bookingnotes,
                'etd' => $etd,
                'created_by' => Auth::id(),
                'vehicle_id' => $vehicleId,
                'days' => $days,
            ]);
        }
        return response()->json(['message' => 'Booking request submitted successfully']);
    }
    public function index(Request $request)
{
    $useractivities =  New UserActivities();
    $useractivities->activity = "Booking Approval Section View";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('view-all-bookings');
    if ($request->ajax()) {
        $status = $request->input('status');
        $searchValue = $request->input('search.value');
        if($status === "New")
        {
        $data = BookingRequest::select([
                'booking_requests.id',
                'booking_requests.calls_id',
                'users.name',
                DB::raw("DATE_FORMAT(booking_requests.date, '%d-%b-%Y') as date"),
                DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                'booking_requests.days',
                'booking_requests.bookingnotes',
                'booking_requests.etd',
                'vehicles.vin',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.model_detail as model_detail',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('vehicles', 'booking_requests.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('quotations', 'booking_requests.calls_id', '=', 'quotations.calls_id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking_requests.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status);
            if (!$hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            if (!empty($searchValue)) {
                $data->where(function ($query) use ($searchValue) {
                    $query->where('booking_requests.days', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking_requests.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking_requests.reason', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking_requests.calls_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('vehicles.vin', 'like', '%' . $searchValue . '%')
                    ->orWhere('brands.brand_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('varaints.name', 'like', '%' . $searchValue . '%')
                    ->orWhere('varaints.detail', 'like', '%' . $searchValue . '%')
                    ->orWhere('so.so_number', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('vehicle.interior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('vehicle.exterior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhere('master_model_lines.model_line', 'like', '%' . $searchValue . '%');
                });
            }
            $data = $data->groupBy('booking_requests.id');
        }
        else if($status === "Approved Without SO") {
            $status = "Approved";
            $data = Booking::select([
                'booking.id',
                DB::raw("DATE_FORMAT(booking.booking_start_date, '%d-%b-%Y') as booking_start_date"),
                DB::raw("DATE_FORMAT(booking.booking_end_date, '%d-%b-%Y') as booking_end_date"),
                DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                'booking.calls_id',
                'vehicles.vin',
                'users.name',
                'brands.brand_name',
                'booking_requests.bookingnotes',
                'booking_requests.etd',
                'varaints.name as variant',
                'varaints.model_detail as model_detail',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('booking_requests', 'booking.booking_requests_id', '=', 'booking_requests.id')
            ->leftJoin('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('quotations', 'booking_requests.calls_id', '=', 'quotations.calls_id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking_requests.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->whereNull('vehicles.so_id')
            ->where('booking_requests.status', $status)
            ->whereDate('booking.booking_end_date', '>=', now());
            if (!$hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            if (!empty($searchValue)) {
                $data->where(function ($query) use ($searchValue) {
                    $query->where('booking_requests.days', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.calls_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.booking_start_date', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.booking_end_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('vehicles.vin', 'like', '%' . $searchValue . '%')
                        ->orWhere('brands.brand_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.detail', 'like', '%' . $searchValue . '%')
                        ->orWhere('so.so_number', 'like', '%' . $searchValue . '%')
                        ->orWhereHas('vehicle.interior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('vehicle.exterior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhere('master_model_lines.model_line', 'like', '%' . $searchValue . '%');
                });
            }            
            $data = $data->groupBy('booking.id');
        }
        else if($status === "Approved With SO") {
            $status = "Approved";
            $data = Booking::select([
                'booking.id',
                DB::raw("DATE_FORMAT(booking.booking_start_date, '%d-%b-%Y') as booking_start_date"),
                DB::raw("DATE_FORMAT(booking.booking_end_date, '%d-%b-%Y') as booking_end_date"),
                DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                'booking.calls_id',
               'users.name',
                'vehicles.vin',
                'booking_requests.bookingnotes',
                'booking_requests.etd',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.model_detail as model_detail',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('booking_requests', 'booking.booking_requests_id', '=', 'booking_requests.id')
            ->leftJoin('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('quotations', 'booking_requests.calls_id', '=', 'quotations.calls_id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking_requests.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status)
            ->whereDate('booking.booking_end_date', '>=', now())
            ->whereNotNull('vehicles.so_id');
            if (!$hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            if (!empty($searchValue)) {
                $data->where(function ($query) use ($searchValue) {
                    $query->where('booking_requests.days', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.calls_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.booking_start_date', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.booking_end_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('vehicles.vin', 'like', '%' . $searchValue . '%')
                        ->orWhere('brands.brand_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.detail', 'like', '%' . $searchValue . '%')
                        ->orWhere('so.so_number', 'like', '%' . $searchValue . '%')
                        ->orWhereHas('vehicle.interior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('vehicle.exterior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhere('master_model_lines.model_line', 'like', '%' . $searchValue . '%');
                });
            }  
            $data = $data->groupBy('booking.id');
        }
        else if($status === "Expire") {
            $status = "Approved";
            $data = Booking::select([
                'booking.id',
                DB::raw("DATE_FORMAT(booking.booking_start_date, '%d-%b-%Y') as booking_start_date"),
                DB::raw("DATE_FORMAT(booking.booking_end_date, '%d-%b-%Y') as booking_end_date"),
                DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                'booking.calls_id',
                'users.name',
                'vehicles.vin',
                'booking_requests.bookingnotes',
                'booking_requests.etd',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.model_detail as model_detail',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('booking_requests', 'booking.booking_requests_id', '=', 'booking_requests.id')
            ->leftJoin('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('quotations', 'booking_requests.calls_id', '=', 'quotations.calls_id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking_requests.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status)
            ->whereDate('booking.booking_end_date', '<', now());
            if (!$hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            if (!empty($searchValue)) {
                $data->where(function ($query) use ($searchValue) {
                    $query->where('booking_requests.days', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.calls_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.booking_start_date', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking.booking_end_date', 'like', '%' . $searchValue . '%')
                        ->orWhere('vehicles.vin', 'like', '%' . $searchValue . '%')
                        ->orWhere('brands.brand_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.detail', 'like', '%' . $searchValue . '%')
                        ->orWhere('so.so_number', 'like', '%' . $searchValue . '%')
                        ->orWhereHas('vehicle.interior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('vehicle.exterior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhere('master_model_lines.model_line', 'like', '%' . $searchValue . '%');
                });
            }  
            $data = $data->groupBy('booking.id');
        }
        else if($status === "Rejected") {
            $status = "Rejected";
            $data = BookingRequest::select([
                'booking_requests.id',
                'booking_requests.calls_id',
                DB::raw("DATE_FORMAT(booking_requests.date, '%d-%b-%Y') as date"),
                DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                'booking_requests.days',
                'users.name',
                'booking_requests.reason',
                'vehicles.vin',
                'booking_requests.bookingnotes',
                'booking_requests.etd',
                'brands.brand_name',
                'varaints.name as variant',
                'varaints.model_detail as model_detail',
                'varaints.detail as variant_details',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
                'vehicles.so_id',
                'so.so_number',
            ])
            ->leftJoin('vehicles', 'booking_requests.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('quotations', 'booking_requests.calls_id', '=', 'quotations.calls_id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
            ->leftJoin('users', 'booking_requests.created_by', '=', 'users.id')
            ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
            ->where('booking_requests.status', $status);
            if (!$hasEditSOPermission) {
                $data = $data->where('booking_requests.created_by', Auth::id());
            }
            if (!empty($searchValue)) {
                $data->where(function ($query) use ($searchValue) {
                    $query->where('booking_requests.days', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking_requests.id', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking_requests.reason', 'like', '%' . $searchValue . '%')
                    ->orWhere('booking_requests.calls_id', 'like', '%' . $searchValue . '%')
                        ->orWhere('vehicles.vin', 'like', '%' . $searchValue . '%')
                        ->orWhere('brands.brand_name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.name', 'like', '%' . $searchValue . '%')
                        ->orWhere('varaints.detail', 'like', '%' . $searchValue . '%')
                        ->orWhere('so.so_number', 'like', '%' . $searchValue . '%')
                        ->orWhereHas('vehicle.interior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('vehicle.exterior', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhere('master_model_lines.model_line', 'like', '%' . $searchValue . '%');
                });
            }
            $data = $data->groupBy('booking_requests.id');
        }
            return DataTables::of($data)
            ->toJson();
    }
    return view('booking.index');
}
public function approval(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Approved the Vehicle Booking";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
            Log::info('Vehicle SO ID updated during approval. (so_id update Detected 1. approval)', [
                'vehicle_id' => $vehicle->id,
                'new_so_id' => $so_id,
                'updated_by' => Auth::user()->email ?? 'system',
                'timestamp' => now(),
            ]);
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
        $useractivities =  New UserActivities();
        $useractivities->activity = "Extended the Time of the Booking Vehicles";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $id = $request->input('id');
        $days = $request->input('days');
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
    public function getbookingvehiclesbb($variantId, $exteriorColorId = null, $interiorColorId = null)
    {
    $useractivities =  New UserActivities();
    $useractivities->activity = "Shifting the Vehicle into Booking List";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    $today = now();
    $query = Vehicles::select([
        'vehicles.vin as vin',
        'vehicles.price as price',
        'vehicles.id',
        'brands.brand_name as brand',
        'master_model_lines.model_line',
        'varaints.name as variant_name',
        'varaints.detail as variant_detail',
        'model_detail as model_detail',
        'interior_color_code.name as interior_color',
        'exterior_color_code.name as exterior_color',
        \DB::raw('CASE WHEN vehicles.movement_grn_id IS NULL THEN "Incoming" ELSE "Arrived" END as grn_status')
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
    public function storedirect(Request $request)
    {
    $validatedData = $request->validate([
        'vehicle_id' => 'required|exists:vehicles,id',
        'booking_start_date' => 'required|date',
        'booking_end_date' => 'required|date|after_or_equal:booking_start_date',
        'salesperson' => 'required|exists:users,id',
        'remarks' => 'nullable|string',
    ]);
    $days = Carbon::parse($validatedData['booking_start_date'])->diffInDays(Carbon::parse($validatedData['booking_end_date']));
    
    $bookingRequest = new BookingRequest();
    $bookingRequest->date = now();
    $bookingRequest->vehicle_id = $validatedData['vehicle_id'];
    $bookingRequest->created_by = $validatedData['salesperson'];
    $bookingRequest->status = 'Approved';
    $bookingRequest->process_by = Auth::id(); // Assuming you meant 'processed_by'
    $bookingRequest->process_date = now();
    $bookingRequest->days = $days;
    $bookingRequest->bookingnotes = $validatedData['remarks'];
    $bookingRequest->save();
    $booking = new Booking();
    $booking->date = now();
    $booking->booking_start_date = $validatedData['booking_start_date'];
    $booking->booking_end_date = $validatedData['booking_end_date'];
    $booking->vehicle_id = $validatedData['vehicle_id'];
    $booking->booking_requests_id = $bookingRequest->id;
    $booking->sales_person_id = $validatedData['salesperson'];
    $booking->save();
    $vehicle = Vehicles::find($validatedData['vehicle_id']);
    if ($vehicle) {
        $vehicle->reservation_start_date = $validatedData['booking_start_date'];
        $vehicle->reservation_end_date = $validatedData['booking_end_date'];
        $vehicle->booking_person_id = $validatedData['salesperson'];
        $vehicle->save();
    }
    return redirect()->route('vehicles.availablevehicles', ['status' => 'Available Stock']);
    }
    public function canceling(Request $request)
    {
        $vehicle_id = $request->input('vehicle_id');
        $bookingrequest = BookingRequest::where('vehicle_id', $vehicle_id)->first();
        $booking = Booking::where('vehicle_id', $vehicle_id)->first();
        if ($bookingrequest) {
            $booking->delete();
            $bookingrequest->status = 'Rejected';
            $bookingrequest->save();
            $vehicle = Vehicles::find($vehicle_id);
            $vehicle->reservation_start_date = Null;
            $vehicle->reservation_end_date = Null;
            $vehicle->booking_person_id = Null;
            $vehicle->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking not found']);
        }
    }
    }
