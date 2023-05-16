<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Calls;
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
            $location = $call->location;
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
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();
    $rowsweek = DB::table('calls')
    ->select('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location','calls.id  as id', 'calls.custom_brand_model',  DB::raw('COUNT(*) as count'))
    ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
    ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
    ->whereBetween('calls.created_at', [$startOfWeek, $endOfWeek])
    ->groupBy('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location')
    ->orderByDesc('count')
    ->limit(8)
    ->get();
    $yesterday = Carbon::yesterday();
    $rowsyesterday = DB::table('calls')
    ->select('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location', 'calls.id  as id', 'calls.custom_brand_model', DB::raw('COUNT(*) as count'))
    ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
    ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
    ->whereDate('calls.created_at', $yesterday)
    ->groupBy('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location')
    ->orderByDesc('count')
    ->limit(8)
    ->get();
    $rowsmonth = DB::table('calls')
    ->select('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location','calls.id  as id', 'calls.custom_brand_model', DB::raw('COUNT(*) as count'))
    ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
    ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
    ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
    ->whereMonth('calls.created_at', '=', date('m'))
    ->groupBy('master_model_lines.brand_id', 'calls_requirement.model_line_id', 'calls.location')
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
$totalleadscount = Calls::where('created_at', '>=', $last30Days)->count();
$totalleadscount7days = Calls::where('created_at', '>=', $last7Days)->count();
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
       return view('home', compact('chartData', 'rowsmonth', 'rowsyesterday', 'rowsweek', 'variants', 'reels', 'totalleads', 'totalleadscount','totalleadscount7days', 'totalvariantss', 'totalvariantcount', 'totalvariantcount7days', 'countpendingpictures', 'countpendingpicturesdays', 'countpendingreels', 'countpendingreelsdays'));
    }      
}
