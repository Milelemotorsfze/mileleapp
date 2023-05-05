<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Calls;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        $calls = DB::table('calls')->select('source', 'location')->get();
        $chartData = [
            'datasets' => []
        ];
        foreach ($calls as $call) {
            $source = $call->source;
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
        return view('home', compact('chartData'));
    }      
}
