<?php
namespace App\Http\Controllers;
use App\Models\AddonDetails;
use App\Models\AddonSellingPrice;
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
    public function index()
    {
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
    ->orderByDesc('count')
    ->limit(8)
    ->get();
    // dd($rowsmonth);
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

       return view('home', compact('totalleadscounttoday','totalvariantcounttoday','chartData',
           'rowsmonth', 'rowsyesterday', 'rowsweek', 'variants', 'reels', 'totalleads', 'totalleadscount','totalleadscount7days',
           'totalvariantss', 'totalvariantcount', 'totalvariantcount7days', 'countpendingpictures', 'countpendingpicturesdays',
           'countpendingreels', 'countpendingreelsdays','pendingSellingPrices','withOutSellingPrices','recentlyAddedAccessories',
            'recentlyAddedSpareParts','recentlyAddedKits'));
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
}
