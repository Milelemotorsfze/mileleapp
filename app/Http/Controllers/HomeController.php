<?php
namespace App\Http\Controllers;
use App\Models\AddonDetails;
use App\Models\UserActivities;
use App\Models\AddonSellingPrice;
use App\Models\ModelHasRoles;
use App\Models\Rejection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Calls;
use Illuminate\Support\Facades\Auth;
use App\Models\AvailableColour;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open the DashBoard";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $calls = DB::table('calls')
            ->select('calls.source', 'calls.location', 'lead_source.source_name')
            ->join('lead_source', 'calls.source', '=', 'lead_source.id')
            ->get();
        $chartData = [
            'datasets' => []
        ];
        foreach ($calls as $call) {
            $source = $call->source_name;
            $location = ucwords(strtolower($call->location));
            $sourceIndex = array_search($source, array_column($chartData['datasets'], 'label'));
            if ($sourceIndex === false) {
                $chartData['datasets'][] = [
                    'label' => $source,
                    'fillColor' => "blue",
                    'data' => [$location => 1]
                ];
            } else {
                if (!isset($chartData['datasets'][$sourceIndex]['data'][$location])) {
                    $chartData['datasets'][$sourceIndex]['data'][$location] = 1;
                } else {
                    $chartData['datasets'][$sourceIndex]['data'][$location]++;
                }
            }
        }
        $startOfWeek = Carbon::now()->subDays(7)->startOfDay();
        $endOfWeek = Carbon::now()->endOfDay();
        $rowsweek = DB::table('calls')
            ->select('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location','calls.id  as id', 'calls.custom_brand_model',  DB::raw('COUNT(*) as count'))
            ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->whereBetween('calls.created_at', [$startOfWeek, $endOfWeek])
            ->whereNotNull('location')
            ->whereNotNull('language')
            ->groupBy('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location')
            ->orderByDesc('count')
            ->limit(8)
            ->get();
        $startOfyes = Carbon::now()->subDays(1)->startOfDay();
        $endOfyes = Carbon::now()->endOfDay();
        $rowsyesterday = DB::table('calls')
            ->select('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location', 'calls.id  as id', 'calls.custom_brand_model', DB::raw('COUNT(*) as count'))
            ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->whereBetween('calls.created_at', [$startOfyes, $endOfyes])
            ->whereNotNull('location')
            ->whereNotNull('language')
            ->groupBy('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location')
            ->orderByDesc('count')
            ->limit(8)
            ->get();
        $startDatethr = Carbon::now()->subDays(30)->startOfDay();
        $endDatethr = Carbon::now()->endOfDay();
        $rowsmonth = DB::table('calls')
            ->select('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location','calls.id  as id', 'calls.custom_brand_model', DB::raw('COUNT(*) as count'))
            ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
            ->whereBetween('calls.created_at', [$startDatethr, $endDatethr])
            ->groupBy('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location')
            ->whereNotNull('location')
            ->whereNotNull('language')
            ->orderByDesc('count')
            ->limit(8)
            ->get();
        $variants = DB::table('varaints as v')
            ->join('available_colour as ac', 'v.id', '=', 'ac.varaint_id')
            ->leftJoin('variants_pictures as vp', 'ac.id', '=', 'vp.available_colour_id')
            ->whereNull('vp.id')
            ->select('v.*', 'ac.*')
            ->limit(20)
            ->get();
        $countpendingpictures = $variants->count();
        $reels = DB::table('varaints as v')
            ->join('available_colour as ac', 'v.id', '=', 'ac.varaint_id')
            ->leftJoin('variants_reels as vs', 'ac.id', '=', 'vs.available_colour_id')
            ->whereNull('vs.id')
            ->select('v.*', 'ac.*')
            ->limit(20)
            ->get();
        $countpendingreels = $reels->count();
        // Fetch the data from the database
        $last30Days = Carbon::now()->subDays(30);
        $last7Days = Carbon::now()->subDays(7);
        $todayl = Carbon::now();
        $todayl = $todayl->startOfDay();
        $variantsdays = DB::table('varaints as v')
            ->join('available_colour as ac', 'v.id', '=', 'ac.varaint_id')
            ->leftJoin('variants_pictures as vp', 'ac.id', '=', 'vp.available_colour_id')
            ->whereNull('vp.id')
            ->where('v.created_at', '>=', $last7Days)
            ->select('v.*', 'ac.*')
            ->get();
        $countpendingpicturesdays = $variantsdays->count();
        $reelsdays = DB::table('varaints as v')
            ->join('available_colour as ac', 'v.id', '=', 'ac.varaint_id')
            ->leftJoin('variants_reels as vs', 'ac.id', '=', 'vs.available_colour_id')
            ->whereNull('vs.id')
            ->where('v.created_at', '>=', $last7Days)
            ->select('v.*', 'ac.*')
            ->get();
        $countpendingreelsdays = $reelsdays->count();
        $totalleadscounttoday = DB::table('calls')->where('created_at', '>=', $todayl)->count();
        $totalleadscount = DB::table('calls')->where('created_at', '>=', $last30Days)->count();
        $totalleadscount7days = DB::table('calls')->where('created_at', '>=', $last7Days)->count();
        $totalvariantcounttoday = AvailableColour::where('created_at', '>=', $todayl)->count();
        $totalvariantcount = AvailableColour::where('created_at', '>=', $last30Days)->count();
        $totalvariantcount7days = AvailableColour::where('created_at', '>=', $last7Days)->count();
        $totalcalls = DB::table('calls')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->where('created_at', ">=", $last30Days)
            ->get();

        // Initialize the labels and data arrays
        $labelss = [];
        $data = [];

        // Loop through the results and populate the arrays
        foreach ($totalcalls as $totalcall) {
            $date = \Carbon\Carbon::parse($totalcall->date)->format('d-M-Y');
            $labelss[] = $date;
            $data[] = $totalcall->total;
        }

        // Define the chart data object
        $totalleads = [
            'labels' => $labelss,
            'datasets' => [
                [
                    'data' => $data,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.1
                ]
            ]
        ];
        $totalvariants = DB::table('available_colour')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->where('created_at', ">=", $last30Days)
            ->get();

        // Initialize the labels and data arrays
        $labelsss = [];
        $data = [];

        // Loop through the results and populate the arrays
        foreach ($totalvariants as $totalvariants) {
            $dates = \Carbon\Carbon::parse($totalvariants->date)->format('d-M-Y');
            $labelsss[] = $dates;
            $data[] = $totalvariants->total;
        }

        // Define the chart data object
        $totalvariantss = [
            'labels' => $labelsss,
            'datasets' => [
                [
                    'data' => $data,
                    'borderColor' => 'rgb(255, 99, 132)',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.1
                ]
            ]
        ];

        /////// parts procurment dashbaord ////////////
        $addonSellingPrices = AddonSellingPrice::with('addonDetails');

        $pendingSellingPrices = $addonSellingPrices->whereHas('addonDetails', function ($query){
                                                $query->where('addon_type_name', 'P');
                                            })
                                            ->where('status', AddonSellingPrice::SELLING_PRICE_STATUS_PENDING)
                                            ->get();

        $addonSellingPricesIds = AddonSellingPrice::groupBy('addon_details_id')->pluck('addon_details_id');
        $withOutSellingPrices = AddonDetails::whereNotIn('id', $addonSellingPricesIds)
                                              ->where('addon_type_name', 'P')->get();

        ////////// end /////////////////////

        $recentlyAddedKits = AddonDetails::where('addon_type_name', 'K')
                            ->orderBy('id','DESC')
                            ->take(10)
                            ->get();
        $recentlyAddedAccessories = AddonDetails::where('addon_type_name', 'P')
            ->orderBy('id','DESC')
            ->take(10)
            ->get();
        $recentlyAddedSpareParts = AddonDetails::where('addon_type_name', 'SP')
            ->orderBy('id','DESC')
            ->take(10)
            ->get();
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-log-activity');
        if ($hasPermission)
        {
            $sales_persons_w = ModelHasRoles::where('role_id', 7)
                ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                ->where('users.status', 'active')
                ->where('users.sales_rap', 'Yes')
                ->get();
            $leadsCount = DB::table('calls')
                ->join('users', 'calls.sales_person', '=', 'users.id')
                ->where('calls.status', '=', 'New')
                ->whereIn('calls.sales_person', $sales_persons_w->pluck('id')->toArray())
                ->groupBy('users.name')
                ->select('users.name as salespersonname', DB::raw('count(*) as lead_count'), 'calls.*')
                ->get();
            $sales_personsname = ModelHasRoles::where('role_id', 7)
                ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                ->where('users.status', 'active')
                ->where('users.sales_rap', 'Yes')
                ->get();
        }
        else{
            $sales_personsname = [];
            $leadsCount = [];
        }
        $saleshasPermission = Auth::user()->hasPermissionForSelectedRole('sale-team-dashboard');
        if ($saleshasPermission) {
            $person = Auth::id();
            $salespersonunder = DB::table('salesteam')->where('lead_person_id', $person)->get();
            $undersalesleads = DB::table('calls')
            ->join('users', 'calls.sales_person', '=', 'users.id')
            ->where('calls.status', '=', 'New')
            ->whereIn('calls.sales_person', $salespersonunder->pluck('person_id')->toArray())
            ->groupBy('users.name')
            ->select('users.name as salespersonname', DB::raw('count(*) as lead_count'), 'calls.*')
            ->get();
        }
        else{
            $undersalesleads = [];
        }
        $hasPermissiongp = Auth::user()->hasPermissionForSelectedRole('gp-dashboard');
        if ($hasPermissiongp) {
            $person = Auth::id();
            $salespersonunder = DB::table('salesteam')->where('lead_person_id', $person)->get();
            $usdToAedRate = 3.67;
            $selectedMonth = $request->get('month') ?? now()->format('Y-m');
            $commissons = DB::table('vehicle_invoice')
                ->join('vehicle_invoice_items', 'vehicle_invoice.id', '=', 'vehicle_invoice_items.vehicle_invoice_id')
                ->join('so', 'vehicle_invoice.so_id', '=', 'so.id')
                ->join('users', 'so.sales_person_id', '=', 'users.id')
                ->leftJoin('vehicle_netsuite_cost', 'vehicle_invoice_items.vehicles_id', '=', 'vehicle_netsuite_cost.vehicles_id')
                ->where(DB::raw("DATE_FORMAT(vehicle_invoice.created_at, '%Y-%m')"), '=', $selectedMonth)
                ->whereIn('so.sales_person_id', $salespersonunder->pluck('person_id')->toArray())
                ->orwhere('so.sales_person_id', $person)
                ->select(
                    'so.sales_person_id',
                    'users.name',
                    DB::raw('COUNT(DISTINCT vehicle_invoice.id) as total_invoices'),
                    DB::raw('COUNT(vehicle_invoice_items.id) as total_invoices_items'),
                    DB::raw('SUM(vehicle_netsuite_cost.cost) as total_vehicle_cost'),
                    DB::raw("SUM(CASE WHEN vehicle_invoice.currency = 'USD' THEN vehicle_invoice_items.rate * $usdToAedRate ELSE vehicle_invoice_items.rate END) as total_rate_in_aed"),
                    DB::raw('GROUP_CONCAT(vehicle_invoice_items.vehicles_id) as all_vehicles_ids'),
                    'vehicle_invoice.currency'
                )
                ->groupBy('so.sales_person_id', 'users.name')
                ->get();
            foreach ($commissons as $item) {
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
        } else {
            $commissons = [];
        }
        $dataforpie = DB::table('lead_rejection')
            ->select('Reason', DB::raw('count(*) as count'))
            ->whereNotNull('Reason') // Exclude null values
            ->groupBy('Reason')
            ->get();
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('dp-dashboard');
        if ($hasPermission) {
            $dpdashboarduae = DB::table('vehicles')
            ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->join('brands', 'varaints.brands_id', '=', 'brands.id')
            ->join('color_codes as int_colours', 'vehicles.int_colour', '=', 'int_colours.id')
            ->join('color_codes as ext_colours', 'vehicles.ex_colour', '=', 'ext_colours.id')
            ->join('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->where('brands.brand_name', 'Toyota')
            ->where('varaints.is_dp_variant', 'Yes')
            ->whereNotIn('vehicles.latest_location', ['102', '153', '147', '38'])
            ->where('purchasing_order.is_demand_planning_po', true)
            ->select('varaints.name as variant_name', 'varaints.id as varaints_id')
            ->distinct()
            ->get();
        $dpdashboardnon = DB::table('vehicles')
            ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->join('brands', 'varaints.brands_id', '=', 'brands.id')
            ->join('color_codes as int_colours', 'vehicles.int_colour', '=', 'int_colours.id')
            ->join('color_codes as ext_colours', 'vehicles.ex_colour', '=', 'ext_colours.id')
            ->join('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->where('brands.brand_name', 'Toyota')
            ->where('varaints.is_dp_variant', 'Yes')
            ->where('vehicles.latest_location', '=', 38)
            ->whereNotIn('vehicles.latest_location', ['102', '153', '147'])
            ->where('purchasing_order.is_demand_planning_po', true)
            ->select('varaints.name as variant_name', 'varaints.id as varaints_id')
            ->distinct()
            ->get();
            return view('home', compact('totalleadscounttoday','totalvariantcounttoday','chartData',
                'rowsmonth', 'rowsyesterday', 'rowsweek', 'variants', 'reels', 'totalleads', 'totalleadscount','totalleadscount7days',
                'totalvariantss', 'totalvariantcount', 'totalvariantcount7days', 'countpendingpictures', 'countpendingpicturesdays',
                'countpendingreels', 'countpendingreelsdays','pendingSellingPrices','withOutSellingPrices','recentlyAddedAccessories',
                'recentlyAddedSpareParts','recentlyAddedKits', 'leadsCount', 'sales_personsname','dpdashboarduae','dpdashboardnon','undersalesleads','commissons','dataforpie'));
        }
        else
        {
            return view('home', compact('totalleadscounttoday','totalvariantcounttoday','chartData',
                'rowsmonth', 'rowsyesterday', 'rowsweek', 'variants', 'reels', 'totalleads', 'totalleadscount','totalleadscount7days',
                'totalvariantss', 'totalvariantcount', 'totalvariantcount7days', 'countpendingpictures', 'countpendingpicturesdays',
                'countpendingreels', 'countpendingreelsdays','pendingSellingPrices','withOutSellingPrices','recentlyAddedAccessories',
                'recentlyAddedSpareParts','recentlyAddedKits', 'leadsCount', 'sales_personsname','undersalesleads','commissons','dataforpie'));
        }
    }
    public function marketingupdatechart(Request $request)
    {
        $startdate = $request->input('start_date');
        $enddate = $request->input('end_date');
        $calls = DB::table('calls')
        ->select('calls.source', 'calls.location', 'lead_source.source_name')
        ->join('lead_source', 'calls.source', '=', 'lead_source.id')
        ->whereDate('calls.created_at', '>=', $startdate)
        ->whereDate('calls.created_at', '<=', $enddate)
        ->get();
        $chartData = [
            'datasets' => []
        ];
        foreach ($calls as $call) {
            $source = $call->source_name;
            $location = ucwords(strtolower($call->location));
            $sourceIndex = array_search($source, array_column($chartData['datasets'], 'label'));
            if ($sourceIndex === false) {
                $chartData['datasets'][] = [
                    'label' => $source,
                    'fillColor' => "blue",
                    'data' => [$location => 1]
                ];
            } else {
                if (!isset($chartData['datasets'][$sourceIndex]['data'][$location])) {
                    $chartData['datasets'][$sourceIndex]['data'][$location] = 1;
                } else {
                    $chartData['datasets'][$sourceIndex]['data'][$location]++;
                }
            }
        }
        return response()->json(['chartData' => $chartData]);
    }
    public function leaddistruition(Request $request) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $sales_persons_w = ModelHasRoles::where('role_id', 7)
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->where('users.status', 'active')
        ->where('users.sales_rap', 'Yes')
        ->get();
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('approve-reservation');
        $query = DB::table('calls')
            ->join('users', 'calls.sales_person', '=', 'users.id')
            ->selectRaw('DATE(calls.created_at) AS call_date, users.name AS sales_person_name');
        if ($hasPermission) {
            $query->selectRaw('SUM(CASE WHEN calls.source = 6 THEN 1 ELSE 0 END) AS call_count_6');
            $query->selectRaw('SUM(CASE WHEN calls.source = 16 THEN 1 ELSE 0 END) AS call_count_16');
            $query->selectRaw('SUM(CASE WHEN calls.source = 35 THEN 1 ELSE 0 END) AS call_count_35');
            $query->selectRaw('SUM(CASE WHEN calls.source = 40 THEN 1 ELSE 0 END) AS call_count_40');
            $query->selectRaw('SUM(CASE WHEN calls.source = 27 THEN 1 ELSE 0 END) AS call_count_27');
            $query->selectRaw('SUM(CASE WHEN calls.source NOT IN (6, 16, 35, 40, 27) THEN 1 ELSE 0 END) AS call_count');
        } else {
            $query->selectRaw('COUNT(calls.id) AS call_count');
        }
        $query->whereDate('calls.created_at', '>=', $startDate)
            ->whereDate('calls.created_at', '<=', $endDate)
            ->whereIn('calls.sales_person', $sales_persons_w->pluck('id')->toArray())
            ->groupBy('call_date', 'sales_person_name'); 
        if ($hasPermission) {
            $query->orderByDesc('calls.id');
        } else {
            $query->orderByDesc('call_count');
        }
        $data = $query->get();
        $response = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data,
        ];
        return response()->json($response);
    }    
    public function leaddistruitiondetail(Request $request) {
        $useractivities =  New UserActivities();
        $useractivities->activity = "View the Lead Distrubution Details";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $data = DB::table('calls')
        ->join('users', 'calls.sales_person', '=', 'users.id')
        ->selectRaw('DATE(calls.created_at) AS call_date, users.name AS sales_person_name, COUNT(calls.id) AS call_count')
        ->whereDate('calls.created_at', '>=', $startDate)
        ->whereDate('calls.created_at', '<=', $endDate)
        ->groupBy('call_date', 'sales_person_name')
        ->orderByDesc('call_count') // Order by call_count in descending order
        ->get();
        return view('calls.leaddistrubtion', compact('data'));
    }

    public function sellingPriceFilter(Request $request) {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Selling Price Filter";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
//        dd($request->all());
        /////// parts procurment dashbaord ////////////
        $addonSellingPrices = AddonSellingPrice::with('addonDetails','CreatedBy','addonDetail.AddonName');
        $addonSellingPricesIds = AddonSellingPrice::groupBy('addon_details_id')->pluck('addon_details_id');

        $pendingSellingPrices = $addonSellingPrices->whereHas('addonDetails', function ($query) use($request){
            $query->where('addon_type_name', $request->addon_type);
        })
            ->where('status', AddonSellingPrice::SELLING_PRICE_STATUS_PENDING)
            ->get();
        $withOutSellingPrices = AddonDetails::with('AddonName')->whereNotIn('id', $addonSellingPricesIds)
            ->where('addon_type_name', $request->addon_type)->get();

        $data['pendingSellingPrices'] = $pendingSellingPrices;
        $data['withOutSellingPrices'] = $withOutSellingPrices;

        return response()->json($data);


    }
    public function leadstatuswise(Request $request) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        $salesPersons = ModelHasRoles::where('role_id', 7)
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.status', 'active')
            ->where('users.sales_rap', 'Yes')
            ->pluck('users.id');
    
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('leads-working-analysis');
    
        $query = DB::table('calls')
            ->join('users', 'calls.sales_person', '=', 'users.id')
            ->whereBetween('calls.created_at', [$startDate, $endDate])
            ->whereIn('calls.sales_person', $salesPersons)
            ->selectRaw('DATE(calls.created_at) AS call_date, users.name AS sales_person_name');
    
        if ($hasPermission) {
            $query->selectRaw('
                SUM(CASE WHEN calls.status = "New" THEN 1 ELSE 0 END) AS call_count_New,
                SUM(CASE WHEN calls.status IN ("contacted", "follow up") THEN 1 ELSE 0 END) AS call_count_contacted,
                SUM(CASE WHEN calls.status = "working" THEN 1 ELSE 0 END) AS call_count_working,
                SUM(CASE WHEN calls.status = "qualify" THEN 1 ELSE 0 END) AS call_count_qualify,
                SUM(CASE WHEN calls.status = "Rejected" THEN 1 ELSE 0 END) AS call_count_Rejected,
                SUM(CASE WHEN calls.status = "converted" THEN 1 ELSE 0 END) AS call_count_converted,
                SUM(CASE WHEN calls.status = "Closed" THEN 1 ELSE 0 END) AS call_count_closed,
                SUM(CASE WHEN calls.status = "Quoted" THEN 1 ELSE 0 END) AS call_count_quoted,
                SUM(CASE WHEN calls.status = "Prospecting" THEN 1 ELSE 0 END) AS call_count_Prospecting,
                SUM(CASE WHEN calls.status = "New Demand" THEN 1 ELSE 0 END) AS call_count_new_demand
            ');
        } else {
            $query->selectRaw('COUNT(calls.id) AS call_count');
        }
    
        $query->groupBy('sales_person_name')
              ->orderByDesc($hasPermission ? 'calls.id' : 'call_count');
    
        $data = $query->get();
    
        return response()->json([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data,
        ]);
    }
    public function getFilteredData(Request $request)
{
    $startDate = $request->start_date;
    $endDate = $request->end_date;
        $filteredData = DB::table('lead_rejection')
        ->select('Reason', DB::raw('count(*) as count'))
        ->whereNotNull('Reason') // Exclude null values
        ->whereBetween('date', [$startDate, $endDate])
        ->groupBy('Reason')
        ->get();
    $labels = $filteredData->pluck('Reason');
    $counts = $filteredData->pluck('count');
    return response()->json(['labels' => $labels, 'counts' => $counts]);
} 
public function showRejectedLeads(Request $request)
{
    $startDate = $request->query('start_date');
    $endDate = $request->query('end_date');
    $reason = $request->query('reason');

    $rejectedLeads = Rejection::where('reason', $reason)
        ->whereBetween('lead_rejection.date', [$startDate, $endDate])
        ->join('calls', 'lead_rejection.call_id', '=', 'calls.id')
        ->leftJoin('users as creators', 'calls.created_by', '=', 'creators.id')
        ->leftJoin('users as assignees', 'calls.sales_person', '=', 'assignees.id')
        ->select(
            'calls.created_at',
            'calls.type as selling_type',
            'calls.name as customer_name',
            'calls.id as customer_id',
            'calls.phone as customer_phone',
            'calls.email as customer_email',
            'calls.language as preferred_language',
            'calls.location',
            'calls.remarks',
            'creators.name as created_by_name',
            'assignees.name as assigned_by_name'
        )
        ->get();

    return view('dailyleads.rejectionlist', compact('rejectedLeads', 'startDate', 'endDate', 'reason'));
}
}