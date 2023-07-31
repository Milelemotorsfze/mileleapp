<?php

namespace App\Http\Controllers;
use App\Models\ColorCode;
use App\Models\VehicleApprovalRequests;
use App\Models\Vehicles;
use App\Models\PurchasingOrder;
use App\Models\Varaint;
use App\Models\grn;
use App\Models\Gdn;
use App\Models\Document;
use App\Models\Documentlog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ModelHasRoles;
use App\Models\So;
use App\Models\Vehicleslog;
use App\Models\Solog;
use App\Models\Remarks;
use App\Models\Warehouse;
use App\Models\VehiclePicture;
use App\Models\MasterModelLines;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        if ($hasPermission) {
            $statuss = "Incoming Stock";
            $data = Vehicles::where('payment_status', $statuss)
                ->where(function ($query) {
                    $query->whereNull('so_id')
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
    
            $columnNames = $request->query('columnName');
            $searchQueries = $request->query('searchQuery');
    
            // Check if any filters were applied
            if (!empty($columnNames) && !empty($searchQueries)) {
                // Loop through the array of column names and search queries
                foreach ($columnNames as $index => $columnName) {
                    $searchQuery = $searchQueries[$index];
                    // Apply filtering logic based on the column name and search query
                    // Customize this part based on your filtering requirements
                    switch ($columnName) {
                        case 'vin':
                            // Split the search query by commas to get individual VIN numbers
                            $vinNumbers = explode(',', $searchQuery);
                            // Apply the filter for each VIN number using OR condition
                            $data = $data->where(function ($query) use ($vinNumbers) {
                                foreach ($vinNumbers as $vin) {
                                    $query->orWhere('vin', 'LIKE', '%' . trim($vin) . '%');
                                }
                            });
                            break;
                        case 'territory':
                            $data->where('territory', 'LIKE', '%' . $searchQuery . '%');
                            break;
                        // Add more cases for other columns if needed
                        default:
                            break;
                    }
                }
            }
    
            $data = $data->paginate(30);
        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status','Pending')
                                                ->groupBy('vehicle_id')->get();
        $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();
        $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $countwarehouse = $warehouses->count();
        $previousYearSold = $this->previousYearSold()->count();
        $previousYearBooked = $this->previousYearBooked()->count();
        $previousMonthSold = $this->previousMonthSold()->count();
        $previousMonthBooked = $this->previousMonthBooked()->count();
        $yesterdaySold = $this->yesterdaySold()->count();
        $yesterdayBooked  = $this->yesterdayBooked()->count();
        $previousYearAvailable  = $this->previousYearAvailable()->count();
        $previousMonthAvailable  = $this->previousMonthAvailable()->count();
        $yesterdayAvailable  = $this->yesterdayAvailable()->count();
        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
        ,'exteriorColours','interiorColours','pendingVehicleDetailForApprovalCount', 'warehouses', 'countwarehouse',
            'warehousesveh', 'warehousesveher','previousYearSold','previousMonthSold','previousYearBooked',
            'previousMonthBooked','yesterdaySold','yesterdayBooked','previousYearAvailable','previousMonthAvailable','yesterdayAvailable'));
        }
        else{
            return redirect()->route('home');
        }
    }
        public function searchData(Request $request)
        {
            $filters = $request->except('page'); // Get all the filters except the 'page' parameter
            $query = PurchasingOrder::query();
            foreach ($filters as $column => $searchQuery) {
            // Apply the filtering logic for each column
                 $query->where($column, 'LIKE', '%' . $searchQuery . '%');
            }
            $data = $query->get();
            return view('your_another_page_view', compact('data'));
        }
    public function stockCountFilter(Request $request) {

        if($request->key) {
            $searchKey = $request->key;
            $vehicleIds = Vehicles::pluck('id');

            if($searchKey == Vehicles::FILTER_PREVIOUS_YEAR_SOLD) {
                $vehicleIds = $this->previousYearSold()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_PREVIOUS_MONTH_SOLD) {
                $vehicleIds = $this->previousMonthSold()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_YESTERDAY_SOLD) {
                $vehicleIds = $this->yesterdaySold()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_PREVIOUS_YEAR_BOOKED) {
                $vehicleIds = $this->previousYearBooked()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_PREVIOUS_MONTH_BOOKED) {
                $vehicleIds = $this->previousMonthBooked()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_YESTERDAY_BOOKED) {
                $vehicleIds = $this->yesterdayBooked()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_PREVIOUS_YEAR_AVAILABLE) {
                $vehicleIds = $this->previousYearAvailable()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_PREVIOUS_MONTH_AVAILABLE) {
                $vehicleIds = $this->previousMonthAvailable()->pluck('id');
            }
            if($searchKey == Vehicles::FILTER_YESTERDAY_AVAILABLE) {
                $vehicleIds = $this->yesterdayAvailable()->pluck('id');
            }
            $data = Vehicles::whereIn('id',$vehicleIds)->get();

        }else{
            $statuss = "Incoming Stock";
            $data = Vehicles::where('payment_status', $statuss)
                ->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                })
                ->paginate(30);
        }
        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status','Pending')
            ->groupBy('vehicle_id')->get();
        $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();
        $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $countwarehouse = $warehouses->count();

        $previousYearSold = $this->previousYearSold()->count();
        $previousYearBooked = $this->previousYearBooked()->count();
        $previousMonthSold = $this->previousMonthSold()->count();
        $previousMonthBooked = $this->previousMonthBooked()->count();
        $yesterdaySold = $this->yesterdaySold()->count();
        $yesterdayBooked  = $this->yesterdayBooked()->count();
        $previousYearAvailable  = $this->previousYearAvailable()->count();
        $previousMonthAvailable  = $this->previousMonthAvailable()->count();
        $yesterdayAvailable  = $this->yesterdayAvailable()->count();

        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
            ,'exteriorColours','interiorColours','pendingVehicleDetailForApprovalCount', 'warehouses', 'countwarehouse',
            'warehousesveh', 'warehousesveher','previousYearSold','previousMonthSold','previousYearBooked',
            'previousMonthBooked','yesterdaySold','yesterdayBooked','previousYearAvailable','previousMonthAvailable','yesterdayAvailable'));
    }
    public function previousYearSold() {
        // logic => if gdn id is there then that vehicle is completed.
        $currentYear = \Carbon\Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
        $countPreviouseYearSold = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw(1))
                    ->from('gdn')
                    ->whereColumn('gdn.id', '=', 'vehicles.gdn_id')
                    ->whereBetween('gdn.date', [$startDate, $endDate]);
            })
            ->get();

        return $countPreviouseYearSold;
    }
    public function previousYearBooked() {
        $currentYear = \Carbon\Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
        $countPreviouseYearBooked = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw(1))
                    ->from('so')
                    ->whereColumn('so.id', '=', 'vehicles.so_id')
                    ->whereBetween('so.so_date', [$startDate, $endDate]);
            })
            ->get();

        return $countPreviouseYearBooked;
    }
    public function previousYearAvailable() {

        $currentYear = \Carbon\Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
        $countPreviouseYearAvailable = \Illuminate\Support\Facades\DB::table('vehicles')
            ->join('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
            ->join('so', 'so.id', '=', 'vehicles.so_id')
            ->whereBetween('gdn.date', [$startDate, $endDate])
            ->whereDate('so.so_date', '>=' , $endDate)
            ->get();

        return $countPreviouseYearAvailable;
    }
    public function previousMonthSold() {
        $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
        $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

        $countLastMonth = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDateLastMonth, $endDateLastMonth) {
                $query->select(DB::raw(1))
                    ->from('gdn')
                    ->whereColumn('gdn.id', '=', 'vehicles.gdn_id')
                    ->whereBetween('gdn.date', [$startDateLastMonth, $endDateLastMonth]);
            })
            ->get();

        return $countLastMonth;
    }
    public function previousMonthBooked() {
        // logic => if gdn id is there then that vehicle is completed.
        $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
        $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

        $countLastMonth = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDateLastMonth, $endDateLastMonth) {
                $query->select(DB::raw(1))
                    ->from('so')
                    ->whereColumn('so.id', '=', 'vehicles.so_id')
                    ->whereBetween('so.so_date', [$startDateLastMonth, $endDateLastMonth]);
            })
            ->get();

        return $countLastMonth;
    }
    public function previousMonthAvailable() {

        $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
        $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

        $countPreviousYearAvailable = \Illuminate\Support\Facades\DB::table('vehicles')
            ->join('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
            ->join('so', 'so.id', '=', 'vehicles.so_id')
            ->whereBetween('gdn.date', [$startDateLastMonth, $endDateLastMonth])
            ->whereDate('so.so_date', '>=' , $endDateLastMonth)
            ->get();

        return $countPreviousYearAvailable;
    }

    public function yesterdaySold() {
        $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');
        $countYesterdaySold = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($yesterday) {
                $query->select(DB::raw(1))
                    ->from('gdn')
                    ->whereColumn('gdn.id', '=', 'vehicles.gdn_id')
                    ->where('gdn.date', $yesterday);
            })
            ->get();

        return $countYesterdaySold;
    }
    public function yesterdayBooked() {
          $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');

        $countYesterdayBooked = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($yesterday) {
                $query->select(DB::raw(1))
                    ->from('so')
                    ->whereColumn('so.id', '=', 'vehicles.so_id')
                    ->where('so.so_date', $yesterday);
            })
            ->get();

        return $countYesterdayBooked;
    }
    public function yesterdayAvailable() {

        $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');

        $countYesterdayAvailable = \Illuminate\Support\Facades\DB::table('vehicles')
            ->join('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
            ->join('so', 'so.id', '=', 'vehicles.so_id')
            ->whereDate('gdn.date', $yesterday)
            ->whereDate('so.so_date', '>=' , $yesterday)
            ->get();

        return $countYesterdayAvailable;
    }

    public function pendingapprovals(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $fieldValues = ['ex_colour', 'int_colour', 'variants_id', 'ppmmyyy', 'inspection_date', 'engine'];
            $statuss = "Incoming Stock";
        $data = Vehicles::where('payment_status', $statuss)
                ->where('latest_location', $warehouseId)
                ->join('vehicle_detail_approval_requests', 'vehicles.id', '=', 'vehicle_detail_approval_requests.vehicle_id')
                ->where('vehicle_detail_approval_requests.status', '=', 'Pending')
                ->where('vehicles.latest_location', '=', $warehouseId) // Replace $warehousesveher->id with $warehouseId
                ->where(function ($query) use ($fieldValues) {
                    $query->whereIn('field', $fieldValues);
                })
    ->where(function ($query) {
        // Include vehicles with 'so_id' is null
        $query->whereNull('so_id')
            // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
            ->orWhereHas('So', function ($query) {
                $query->where('sales_person_id', Auth::user()->role_id);
            });
    })
    ->get();
        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status','Pending')
        ->groupBy('vehicle_id')->get();
        $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();
        $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $countwarehouse = $warehouses->count() ?? 0;
        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
        ,'exteriorColours','interiorColours','pendingVehicleDetailForApprovalCount', 'warehouses', 'countwarehouse', 'warehousesveh', 'warehousesveher'));
        }
        else{
            return redirect()->route('home');
        }
    }
    public function pendinginspection(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $statuss = "Incoming Stock";
            $data = Vehicles::where('payment_status', $statuss)
            ->where('latest_location', $warehouseId)
            ->whereNull('inspection_date')
            ->where(function ($query) {
                // Include vehicles with 'so_id' is null
                $query->whereNull('so_id')
                    // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                    ->orWhereHas('So', function ($query) {
                        $query->where('sales_person_id', Auth::user()->role_id);
                    });
            })
            ->get();
        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status','Pending')
        ->groupBy('vehicle_id')->get();
        $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();
        $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $countwarehouse = $warehouses->count() ?? 0;
        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
        ,'exteriorColours','interiorColours','pendingVehicleDetailForApprovalCount', 'warehouses', 'countwarehouse', 'warehousesveh', 'warehousesveher'));
        }
        else{
            return redirect()->route('home');
        }
    }
    public function incomingstocks(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        if ($hasPermission) {
            $statuss = "Incoming Stock";
            $data = Vehicles::where('payment_status', $statuss)
            ->whereNull('grn_id')
            ->whereNull('gdn_id')
            ->where(function ($query) {
                // Include vehicles with 'so_id' is null
                $query->whereNull('so_id')
                    // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                    ->orWhereHas('So', function ($query) {
                        $query->where('sales_person_id', Auth::user()->role_id);
                    });
            })
            ->get();
        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status','Pending')
        ->groupBy('vehicle_id')->get();
        $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();
        $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer'])->get();
        $countwarehouse = $warehouses->count();
        return view('vehicles.index', compact('data', 'varaint', 'sales', 'datapending'
        ,'exteriorColours','interiorColours','pendingVehicleDetailForApprovalCount', 'warehouses', 'countwarehouse', 'warehousesveh', 'warehousesveher'));
        }
        else{
            return redirect()->route('home');
        }
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(string $id)
    {
        //
    }
    public function getVehicleDetails(Request $request) {
        $variant = Varaint::find($request->variant_id);
        $brand = $variant->brand->brand_name ?? '';
        $data['brand'] = $brand;
        $data['model_line'] = $variant->master_model_lines->model_line ?? '';
        $data['my'] = $variant->my ?? '';
        $data['model_detail'] = $variant->model_detail ?? '';
        $data['seat'] = $variant->seat ?? '';
        $data['fuel_type'] = $variant->fuel_type ?? '';
        $data['gearbox'] = $variant->gearbox ?? '';
        $data['steering'] = $variant->steering ?? '';
        $data['upholestry'] = $variant->upholestry ?? '';
        $data['detail'] = $variant->detail ?? '';
        return $data;
    }

    public function updatevehiclesdata(Request $request)
    {
        $vehiclesId = $request->input('vehicles_id');
        $column = $request->input('column');
        $value = $request->input('value');
        if($column === "vin")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->vin = $value;
        $vehicle->save();
        }
        if($column === "int_colour")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->int_colour = $value;
        $vehicle->save();
        }
        if($column === "ex_colour")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->ex_colour = $value;
        $vehicle->save();
        }
        if($column === "engine")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->engine = $value;
        $vehicle->save();
        }
        if($column === "remarks")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->remarks = $value;
        $vehicle->save();
        }
        if($column === "territory")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->territory = $value;
        $vehicle->save();
        }
        if($column === "documzinout")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->documzinout = $value;
        $vehicle->save();
        }
        if($column === "ppmmyyy")
        {
        $vehicle = Vehicles::find($vehiclesId);
        $vehicle->ppmmyyy = $value;
        $vehicle->save();
        }
        if($column === "variants_name")
        {
            $variant = Varaint::where('name', $value)->first();
            if ($variant) {
                Vehicles::where('id', $vehiclesId)
                ->update(['varaints_id' => $variant->id]);
            }
        }
        if($column === "import_type")
        {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->import_type = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->import_type = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
        }
        if($column === "owership")
        {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->owership = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->owership = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
        }
        if($column === "document_with")
        {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->document_with = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->document_with = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }

        }
        return response()->json(['message' => 'Vehicle data updated successfully']);
    }
    public function fatchvariantdetails(Request $request)
  {
    $variantName = $request->input('value');
    $result = DB::table('varaints')
    ->join('brands', 'varaints.brands_id', '=', 'brands.id')
    ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->where('varaints.name', $variantName)
    ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat','varaints.gearbox', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
    ->first();
    $responseData = [
        'varaints_detail' => $result->detail ?? null,
        'brand_name' => $result->brand_name ?? null,
        'model_line' => $result->model_line ?? null,
        'my' => $result->my ?? null,
        'upholestry' => $result->upholestry ?? null,
        'steering' => $result->steering ?? null,
        'fuel' => $result->fuel_type ?? null,
        'seat' => $result->seat ?? null,
        'gearbox' => $result->gearbox ?? null,
        'vehicles_id' => $request->input('vehicles_id'),
    ];
    return response()->json($responseData);
    }
    public function updatedata(Request $request)
    {
        $updatedData = $request->json()->all();
        foreach ($updatedData as $data) {
            $vehicleId = $data['id'];
            $fieldName = $data['name'];
            $fieldValue = $data['value'];
            $vehicle = Vehicles::find($vehicleId);
            if ($vehicle) {
                if (in_array($fieldName, ['so_number', 'so_date', 'sales_person_id'])) {
                    // Handling 'so_number', 'so_date', and 'sales_person_id' fields
                    $so_id = $vehicle->so_id;
                    $so = $so_id ? So::find($so_id) : new So();

                    $oldValue = $so->$fieldName ?? null;
                    $newValue = $fieldValue;
                    // Save changes to the log if the old and new values differ and the field is not sales_person_id
                    if ($oldValue !== $newValue) {
                        $soLog = new SoLog();
                        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        $currentDateTime = Carbon::now($dubaiTimeZone);
                        $soLog->time = $currentDateTime->toTimeString();
                        $soLog->date = $currentDateTime->toDateString();
                        $soLog->status = 'Update SO';
                        $soLog->so_id = $so_id;
                        $soLog->field = $fieldName;
                        $soLog->old_value = $oldValue;
                        $soLog->new_value = $newValue;
                        $soLog->created_by = auth()->user()->id;
                        $soLog->role = Auth::user()->selectedRole;
                        $soLog->save();

                        if ($newValue !== null) {
                            $approvalLog = new VehicleApprovalRequests();
                            $approvalLog->vehicle_id = $vehicleId;
                            $approvalLog->status = 'Pending';
                            $approvalLog->field = $fieldName;
                            $approvalLog->old_value = $oldValue;
                            $approvalLog->new_value = $fieldValue;
                            $approvalLog->updated_by = auth()->user()->id;
                            $approvalLog->save();
                        }
                    }
                }
                elseif (in_array($fieldName, ['import_type', 'document_with', 'bl_number', 'owership'])) {
                    $documents_id = $vehicle->documents_id; // Corrected assignment
                    $document = $documents_id ? Document::find($documents_id) : new Document();
                    $oldValue = $document->$fieldName ?? null;
                    $newValue = $fieldValue;
                    // Save changes to the log if the old and new values differ
                    if ($oldValue !== $newValue) {
                                $documentLog = new DocumentLog();
                                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                                $currentDateTime = Carbon::now($dubaiTimeZone);
                                $documentLog->time = $currentDateTime->toTimeString();
                                $documentLog->date = $currentDateTime->toDateString();
                                $documentLog->status = 'Update Document';
                                $documentLog->documents_id = $documents_id;
                                $documentLog->field = $fieldName;
                                $documentLog->old_value = $oldValue;
                                $documentLog->new_value = $newValue;
                                $documentLog->created_by = auth()->user()->id;
                                $documentLog->role = Auth::user()->selectedRole;
                                $documentLog->save();
                                if ($newValue !== null) {
                                $approvalLog = new VehicleApprovalRequests();
                                $approvalLog->vehicle_id = $vehicleId;
                                $approvalLog->status = 'Pending';
                                $approvalLog->field = $fieldName;
                                $approvalLog->old_value = $oldValue;
                                $approvalLog->new_value = $fieldValue;
                                $approvalLog->updated_by = auth()->user()->id;
                                $approvalLog->save();
                            }
                            }
                    }
                    elseif (in_array($fieldName, ['warehouse-remarks', 'sales-remarks'])) {
                        $department = ($fieldName === 'sales-remarks') ? 'sales' : 'warehouse';
                        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        $currentDateTime = Carbon::now($dubaiTimeZone);
                        if ($fieldValue !== null) {
                            $fieldValue = rtrim($fieldValue, 'View All');
                            $existingRemark = Remarks::where('vehicles_id', $vehicleId)
                            ->where('department', $department)
                            ->where('remarks', $fieldValue)
                            ->first();
                            if (!$existingRemark) {
                            $remarks = new Remarks();
                            $remarks->vehicles_id = $vehicleId;
                            $remarks->department = $department;
                            $remarks->date = $currentDateTime->toDateString();
                            $remarks->time = $currentDateTime->toTimeString();
                            $remarks->remarks = $fieldValue;
                            $remarks->created_by = auth()->user()->id;
                            $remarks->save();
                            $vehicleslog = new Vehicleslog();
                            $vehicleslog->time = $currentDateTime->toTimeString();
                            $vehicleslog->date = $currentDateTime->toDateString();
                            $vehicleslog->status = 'Adding New Remarks';
                            $vehicleslog->vehicles_id = $vehicleId;
                            $vehicleslog->field = $fieldName;
                            $vehicleslog->old_value = "";
                            $vehicleslog->new_value = $fieldValue;
                            $vehicleslog->created_by = auth()->user()->id;
                            $vehicleslog->save();
                        }
                    }
                    }
            else {
                $oldValues = $vehicle->getAttributes();
                $changes = [];
                foreach ($oldValues as $field => $oldValue) {
                    if ($field !== 'created_at' && $field !== 'updated_at') {
                        $newValue = $field === $fieldName ? $fieldValue : $vehicle->$field;
                        if ($oldValue != $newValue) {
                            $changes[$field] = [
                                'old_value' => $oldValue,
                                'new_value' => $newValue,
                            ];
                        }
                    }
                }
                if (!empty($changes)) {
                    // Save approval log if the old value is null and the new value is not null
                    if ($fieldValue !== null) {
                        // Update the field in the 'Vehicles' table with the new value
                        $vehicle->$fieldName = $fieldValue;
                        // $vehicle->save();
                        // // Save vehicle log for the specific field
                        // $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        // $currentDateTime = Carbon::now($dubaiTimeZone);
                        // $vehicleslog = new Vehicleslog();
                        // $vehicleslog->time = $currentDateTime->toTimeString();
                        // $vehicleslog->date = $currentDateTime->toDateString();
                        // $vehicleslog->status = 'Update QC Values';
                        // $vehicleslog->vehicles_id = $vehicleId;
                        // $vehicleslog->field = $fieldName;
                        // $vehicleslog->old_value = $oldValues[$fieldName];
                        // $vehicleslog->new_value = $fieldValue;
                        // $vehicleslog->created_by = auth()->user()->id;
                        // $vehicleslog->save();
                        $approvalLog = new VehicleApprovalRequests();
                        $approvalLog->vehicle_id = $vehicleId;
                        $approvalLog->status = 'Pending';
                        $approvalLog->field = $fieldName;
                        $approvalLog->old_value = $oldValues[$fieldName];
                        $approvalLog->new_value = $fieldValue;
                        $approvalLog->updated_by = auth()->user()->id;
                        $approvalLog->save();
                    }
                }

            }
        }
    }

    $responseData = ['status' => 'success', 'message' => 'Data updated successfully'];
    return response()->json($responseData);
}
    public function updateso(Request $request)
    {
//        return $request->all();
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicleId)
        {
            $vehicle = Vehicles::find($vehicleId);
            $soId = $vehicle->so_id;
            if ($soId)
            {
                info("soid existing");
                $so = So::find($soId);
                if(!empty($so->so_number)) {
                    if($so->so_number != $request->so_numbers[$key])
                    {
                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'so_number';
                        $vehicleDetailApproval->old_value = $so->so_number;
                        $vehicleDetailApproval->new_value = $request->so_numbers[$key];
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }

                }else {
                    if($so->so_number != $request->so_numbers[$key])
                    {
                        $solog = new Solog();

                        $solog->time = $currentDateTime->toTimeString();
                        $solog->date = $currentDateTime->toDateString();
                        $solog->status = 'Update Sales Values';
                        $solog->so_id = $soId;
                        $solog->field = 'so_number';
                        $solog->old_value = $so->so_number;
                        $solog->new_value = $request->so_numbers[$key];
                        $solog->created_by = auth()->user()->id;
                        $solog->save();
                        $so->so_number = $request->so_numbers[$key];

                        $so->save();
                    }
                }

                $oldSoDate = Carbon::parse($so->so_date)->format('d M Y');
                $newSoDate = Carbon::parse($request->so_dates[$key])->format('d M Y');
                if(!empty($so->so_date)) {
                    if($oldSoDate != $newSoDate) {
                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'so_date';
                        $vehicleDetailApproval->old_value = $so->so_date;
                        $vehicleDetailApproval->new_value = $request->so_dates[$key];
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }

                }else{
                    if($oldSoDate != $newSoDate)
                    {
                        $solog = new Solog();
//                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
//                    $currentDateTime = Carbon::now($dubaiTimeZone);
                        $solog->time = $currentDateTime->toTimeString();
                        $solog->date = $currentDateTime->toDateString();
                        $solog->status = 'Update Sales Values';
                        $solog->so_id = $soId;
                        $solog->field = 'so_date';
                        $solog->old_value = $so->so_date;
                        $solog->new_value = $request->so_dates[$key];
                        $solog->created_by = auth()->user()->id;
                        $solog->save();
                        $so->so_date = $request->so_dates[$key];

                        $so->save();
                    }
                }
            }
            else
            {
                // so should be unique then so number is updtaing existing so number.
                $existingSo = So::where('so_number', $request->so_numbers[$key])->first();
//                if ($existingSo) {
//                    // Update existing So
//                    if($existingSo->so_number != $request->so_numbers[$key])
//                    {
                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'so_number';
                        $vehicleDetailApproval->old_value = $so->so_number;
                        $vehicleDetailApproval->new_value = $request->so_numbers[$key];
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
//                    }
                    $oldSoDate = Carbon::parse($so->so_date)->format('d M Y');
                    $newSoDate = Carbon::parse($request->so_dates[$key])->format('d M Y');
                    if($oldSoDate != $newSoDate)
                    {
                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'so_date';
                        $vehicleDetailApproval->old_value = $so->so_date;
                        $vehicleDetailApproval->new_value = $request->so_dates[$key];
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }
//                    $existingSo->save();

//                }
            }

            if ($request->reservation_start_dates[$key]) {
                $newReservationStartDate = $request->reservation_start_dates[$key];
                $isStartDateChanged = $vehicle->reservation_start_date != $newReservationStartDate;

                if (!empty($vehicle->reservation_start_date)) {
                    if ($vehicle->reservation_start_date != $newReservationStartDate) {

                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'reservation_start_date';
                        $vehicleDetailApproval->old_value = $vehicle->reservation_start_date;
                        $vehicleDetailApproval->new_value = $newReservationStartDate;
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }
                }else{
                    if ($vehicle->reservation_start_date != $newReservationStartDate) {
                        $vehicle->reservation_start_date = $newReservationStartDate;

                        $reservationStartDateLog = new Vehicleslog();
                        $reservationStartDateLog->time = $currentDateTime->toTimeString();
                        $reservationStartDateLog->date = $currentDateTime->toDateString();
                        $reservationStartDateLog->status = 'Update Vehicle Values';
                        $reservationStartDateLog->vehicles_id = $vehicleId;
                        $reservationStartDateLog->field = 'reservation_start_date';
                        $reservationStartDateLog->old_value = $vehicle->getOriginal('reservation_start_date');
                        $reservationStartDateLog->new_value = $newReservationStartDate;
                        $reservationStartDateLog->created_by = auth()->user()->id;
                        $reservationStartDateLog->save();
                    }
                }
            }
            if ($request->reservation_end_dates[$key]) {
                $newReservationEndDate = $request->reservation_end_dates[$key];
                $isEndDateChanged = $vehicle->reservation_end_date != $newReservationEndDate;
                if (!empty($vehicle->reservation_end_date))
                {
                    if($vehicle->reservation_end_date != $newReservationEndDate) {
                        $vehicleDetailApproval = new VehicleApprovalRequests();

                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'reservation_end_date';
                        $vehicleDetailApproval->old_value = $vehicle->reservation_end_date;
                        $vehicleDetailApproval->new_value = $newReservationEndDate;
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }

                }else {
                    if($vehicle->reservation_end_date != $newReservationEndDate) {
                        $vehicle->reservation_end_date = $newReservationEndDate;

                        $reservationEndDateLog = new Vehicleslog();
                        $reservationEndDateLog->time = $currentDateTime->toTimeString();
                        $reservationEndDateLog->date = $currentDateTime->toDateString();
                        $reservationEndDateLog->status = 'Update Vehicle Values';
                        $reservationEndDateLog->vehicles_id = $vehicleId;
                        $reservationEndDateLog->field = 'reservation_end_date';
                        $reservationEndDateLog->old_value = $vehicle->getOriginal('reservation_end_date');
                        $reservationEndDateLog->new_value = $newReservationEndDate;
                        $reservationEndDateLog->created_by = auth()->user()->id;
                        $reservationEndDateLog->save();
                    }
                }
            }

            if($request->remarks[$key]){

                $remarksdata = new Remarks();

                $remarksdata->time = $currentDateTime->toTimeString();
                $remarksdata->date = $currentDateTime->toDateString();
                $remarksdata->vehicles_id = $vehicleId;
                $remarksdata->remarks = $request->remarks[$key];
                $remarksdata->created_by = auth()->user()->id;
                $remarksdata->department = "Sales";
                $remarksdata->created_at = $currentDateTime;
                $remarksdata->save();
//                if($soID) {
//                    $remarklog = new Solog();
//                    $remarklog->time = $currentDateTime->toTimeString();
//                    $remarklog->date = $currentDateTime->toDateString();
//                    $remarklog->status = 'New Created';
//                    $remarklog->so_id = $soID;
//                    $remarklog->field = 'remarks';
//                    $remarklog->new_value = $request->remarks[$key];
//                    $remarklog->created_by = auth()->user()->id;
//                    $remarklog->save();
//                }

            }
            // Save changes in the vehicles table
            $vehicle->save();

        }
        ///  Currenty not updating /////////////
        // Update payment_percentage if changed
//        if ($request->has('payment_percentage')) {
//            $newPaymentPercentage = $request->input('payment_percentage');
//            if ($vehicle->payment_percentage != $newPaymentPercentage) {
//                $vehicle->payment_percentage = $newPaymentPercentage;
//                $paymentPercentageLog = new Vehicleslog();
//                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
//                $currentDateTime = Carbon::now($dubaiTimeZone);
//                $paymentPercentageLog->time = $currentDateTime->toTimeString();
//                $paymentPercentageLog->date = $currentDateTime->toDateString();
//                $paymentPercentageLog->status = 'Update Vehicle Values';
//                $paymentPercentageLog->vehicles_id = $vehicle;
//                $paymentPercentageLog->field = 'payment_percentage';
//                $paymentPercentageLog->old_value = $vehicle->getOriginal('payment_percentage');
//                $paymentPercentageLog->new_value = $newPaymentPercentage;
//                $paymentPercentageLog->created_by = auth()->user()->id;
//                $paymentPercentageLog->save();
//            }
//        }
            // Update reservation_end_date if changed

        return redirect()->back()->with('success', 'Sales details sent for approval successfully.');
    }
    public function deletes($id)
    {
    $vehicle = Vehicles::find($id);
    if ($vehicle->grn_id === null) {
        $vehicle->status = 'cancel';
        $vehicle->save();
        return redirect()->back()->with('success', 'Vehicle status updated to "cancel" successfully.');
    } else {
        return redirect()->back()->with('error', 'Vehicle has already been delivered and cannot be canceled.');
    }
    }
    public function viewLogDetails($id)
    {
        $vehicle = Vehicles::find($id);
        $documentsLog = Documentlog::with('roleName')->where('documents_id', $vehicle->documents_id);
        $soLog = Solog::with('roleName')->where('so_id', $vehicle->so_id);
        $vehiclesLog = Vehicleslog::with('roleName')->where('vehicles_id', $vehicle->id);
        $mergedLogs = $documentsLog->union($soLog)->union($vehiclesLog)->orderBy('updated_at')->get();
//        return $mergedLogs;
        // $mergedLogs = Vehicles::all();
        $pendingVehicleDetailApprovalRequests = VehicleApprovalRequests::where('vehicle_id', $id)->orderBy('id','DESC')->get();

        $previousId = Vehicles::where('id', '<', $id)->max('id');
        $nextId = Vehicles::where('id', '>', $id)->min('id');
        return view('vehicles.vehicleslog', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ], compact('mergedLogs', 'vehicle','pendingVehicleDetailApprovalRequests'));
    }
    public function  viewremarks(Request $request,$id)
    {
        $remarks = Remarks::where('vehicles_id', $id)->where('department', 'Sales')->get();
        if($request->type == 'WareHouse') {
            $remarks = Remarks::where('vehicles_id', $id)->where('department', 'WareHouse')->get();
        }
        // sales
        $previousId = Vehicles::where('id', '<', $id)->max('id');
        $nextId = Vehicles::where('id', '>', $id)->min('id');
        return view('vehicles.viewremarks', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ], compact('remarks'));
    }
    public function updatelogistics(Request $request)
    {
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicle) {
            $vehicle = Vehicles::find($vehicle);
            $documents_id = $vehicle->documents_id;
            if ($documents_id) {
                $documents = Document::find($documents_id);

                if($documents->import_type != $request->import_types[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'import_type';
                    $documentlog->old_value = $documents->import_type;
                    $documentlog->new_value = $request->import_types[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->import_type = $request->import_types[$key];
                }
                if($documents->owership != $request->owerships[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'owership';
                    $documentlog->old_value = $documents->owership;
                    $documentlog->new_value = $request->owerships[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->owership = $request->owerships[$key];
                }
                if($documents->document_with != $request->documents_with[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'document_with';
                    $documentlog->old_value = $documents->document_with;
                    $documentlog->new_value = $request->documents_with[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->document_with = $request->documents_with[$key];
                }
                if($documents->bl_number != $request->bl_numbers[$key])
                {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'bl_number';
                    $documentlog->old_value = $documents->bl_number;
                    $documentlog->new_value = $request->bl_numbers[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->bl_number = $request->bl_numbers[$key];
                }
                $documents->save();

            }
            else {
                if(!empty($request->import_types[$key]) || !empty($request->owerships[$key]) ||
                    !empty($request->documents_with[$key]) || !empty($request->bl_numbers[$key]))
                {
                    $documents = new Document();
                    $documents->import_type = $request->import_types[$key];
                    $documents->owership = $request->owerships[$key];
                    $documents->document_with = $request->documents_with[$key];
                    $documents->bl_number = $request->bl_numbers[$key];
                    $documents->save();
                    $documents_id = $documents->id;

                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'New Created';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();
                    $vehicle->documents_id = $documents_id;
                    $vehicle->save();
                }

            }
        }
//         $vehicleId = $request->input('vehicle_id');

        return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function viewpictures($id)
    {
    $vehiclePictures = VehiclePicture::where('vehicle_id', $id)->get();
    return view('vehicle_pictures.show', compact('vehiclePictures'));
    }
    public function updatewarehouse(Request $request)
    {
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicleId) {
            $vehicle = Vehicles::find($vehicleId);
//            $vehicle->conversion = $request->conversions[$key];

            if($vehicle->remarks != $request->warehouse_remarks[$key]) {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $vehicleId;
                $vehicleslog->field = 'remarks';
                $vehicleslog->old_value = $vehicle->remarks;
                $vehicleslog->new_value = $request->warehouse_remarks[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $remarksdata = new Remarks();

                $remarksdata->time = $currentDateTime->toTimeString();
                $remarksdata->date = $currentDateTime->toDateString();
                $remarksdata->vehicles_id = $vehicleId;
                $remarksdata->remarks = $request->warehouse_remarks[$key];
                $remarksdata->created_by = auth()->user()->id;
                $remarksdata->department = "WareHouse";
                $remarksdata->created_at = $currentDateTime;
                $remarksdata->save();

                $vehicle->remarks = $request->warehouse_remarks[$key];

            }
            if($vehicle->conversion != $request->conversions[$key]) {
                info("conversion is changed");
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $vehicleId;
                $vehicleslog->field = 'conversion';
                $vehicleslog->old_value = $vehicle->conversion;
                $vehicleslog->new_value = $request->conversions[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
                $vehicle->conversion = $request->conversions[$key];

            }
            $vehicle->save();
        }

        return redirect()->back()->with('success', 'Details updated successfully.');
    }

    public function getModelLines($brandId)
    {
        $modelLines = MasterModelLines::where('brand_id', $brandId)->get();
        return response()->json($modelLines);
    }
    }
