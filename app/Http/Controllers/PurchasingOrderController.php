<?php

namespace App\Http\Controllers;

use App\Models\ApprovedLetterOfIndentItem;
use App\Models\LetterOfIndentItem;
use App\Models\LOIItemPurchaseOrder;
use App\Models\MasterModel;
use App\Models\PFI;
use App\Models\PurchasingOrderEventsLog;
use App\Models\PurchasingOrder;
use App\Models\MasterShippingPorts;
use App\Models\PurchasingOrderItems;
use App\Models\PurchasingOrderSwiftCopies;
use App\Models\SupplierInventory;
use Illuminate\Http\Request;
use App\Models\Varaint;
use App\Models\SupplierAccount;
use App\Models\ColorCode;
use App\Models\Brand;
use App\Models\Country;
use App\Models\MasterModelLines;
use App\Models\Supplier;
use App\Models\Vehicles;
use App\Models\VehiclePurchasingCost;
use App\Models\Movement;
use App\Models\PaymentTerms;
use App\Models\PaymentLog;
use App\Models\User;
use App\Models\Vehicleslog;
use Carbon\Carbon;
use App\Models\ModelHasRoles;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonTimeZone;
use App\Models\UserActivities;
use App\Models\Purchasinglog;
use App\Models\PurchasedOrderPaidAmounts;
use App\Models\VendorPaymentAdjustments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplierAccountTransaction;
use App\Models\PurchasedOrderPriceChanges;
use App\Models\PurchasedOrderMessages;
use App\Models\PurchasedOrderReplies;


class PurchasingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Purchasing Order Index Page View";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
        if(Auth::user()->hasPermissionForSelectedRole('demand-planning-po-list')){
            $demandPlanningPoIds = LOIItemPurchaseOrder::groupBy('purchase_order_id')->pluck('purchase_order_id');
//            return $demandPlanningPoIds;
            // add migrated user Ids
            $Ids = ['16'];
            $Ids[] = $userId;
            $data = PurchasingOrder::with('purchasing_order_items')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('vehicles')
                      ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                      ->where('vehicles.gdn_id', '=', null);
            })
            ->whereHas('vehicles', function ($query) {
                $query->whereNotNull('id');
            })
            ->orderBy('po_date', 'desc')
            ->get();
        }else{
            $data = PurchasingOrder::with('purchasing_order_items')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('vehicles')
              ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
              ->where('vehicles.gdn_id', '=', null);
    })
    ->whereHas('vehicles', function ($query) {
        $query->whereNotNull('id');
    })
    ->orderBy('po_date', 'desc')
    ->get();
        }
    }
    else
    {
        if(Auth::user()->hasPermissionForSelectedRole('demand-planning-po-list')){
            $demandPlanningPoIds = LOIItemPurchaseOrder::groupBy('purchase_order_id')->pluck('purchase_order_id');
//            return $demandPlanningPoIds;
            // add migrated user Ids
            $Ids = ['16'];
            $Ids[] = $userId;
            $data = PurchasingOrder::with('purchasing_order_items')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('vehicles')
                      ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                      ->where('vehicles.gdn_id', '=', null);
            })
            ->whereHas('vehicles', function ($query) {
                $query->whereNotNull('id');
            })
            ->orderBy('po_date', 'desc')
            ->get();
        }else{
            $data = PurchasingOrder::with('purchasing_order_items')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('vehicles')
                      ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                      ->where('vehicles.gdn_id', '=', null);
            })
            ->whereHas('vehicles', function ($query) {
                $query->whereNotNull('id');
            })
            ->orderBy('po_date', 'desc')
            ->get();
        }
    }
        return view('warehouse.index', compact('data'));
    }
    public function filter($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')->where('status', $status)->get();
        return view('warehouse.index', compact('data'));
    }
    public function filtercancel($status)
    {
        $data = PurchasingOrder::with('purchasing_order_items')->where('status', $status)->get();
        return view('warehouse.index', compact('data'));
    }
    public function filterapprovedonly($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Approved');
    })
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else{
        $data = PurchasingOrder::with('purchasing_order_items')->where('purchasing_order.status', 'Approved')
        // ->where(function ($query) use ($userId) {
        //     $query->where('purchasing_order.created_by', $userId)
        //         ->orWhere('purchasing_order.created_by', 16);
        // })
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Approved');
    })
        ->groupBy('purchasing_order.id')
        ->get();
        }
        return view('warehouse.index', compact('data'));
    }
    public function filterapproved($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('vehicles')
                ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                ->where('purchasing_order.status', '<>', 'Cancelled')
                ->where(function ($query) {
                    $query->where('status', 'Request for Payment')
                        ->orWhere(function ($query) {
                            $query->whereNotIn('payment_status', ['Payment Initiate Request Rejected', 'Request Rejected', 'Payment Release Rejected', 'Incoming Stock'])
                                ->where(function ($query) {
                                    $query->whereNotNull('payment_status')
                                        ->where('payment_status', '<>', '');
                                });
                        });
                });
        })
        ->groupBy('purchasing_order.id')
        ->get();
    }
        else{
            $data = PurchasingOrder::with('purchasing_order_items')
            // ->where(function ($query) use ($userId) {
            //     $query->where('purchasing_order.created_by', $userId)
            //         ->orWhere('purchasing_order.created_by', 16);
            // })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('purchasing_order.status', '<>', 'Cancelled')
                    ->where(function ($query) {
                        $query->where('status', 'Request for Payment')
                            ->orWhere(function ($query) {
                                $query->whereNotIn('payment_status', ['Payment Initiate Request Rejected', 'Request Rejected', 'Payment Release Rejected', 'Incoming Stock'])
                                    ->where(function ($query) {
                                        $query->whereNotNull('payment_status')
                                            ->where('payment_status', '<>', '');
                                    });
                            });
                    });
            })
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data'));
    }
    public function filterincomings($status)
{
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
    $data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', 'Approved')
    ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->whereNotIn('payment_status', ['Payment Rejected', 'Payment Release Rejected', 'Payment Initiate Request Rejected', 'Incoming Stock']);
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    // ->where(function ($query) use ($userId) {
    //     $query->where('purchasing_order.created_by', $userId)
    //         ->orWhere('purchasing_order.created_by', 16);
    // })
    ->where('status', 'Approved')
    ->whereNotExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->whereNotIn('payment_status', ['Payment Rejected', 'Payment Release Rejected', 'Payment Initiate Request Rejected', 'Incoming Stock']);
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
    return view('warehouse.index', compact('data'));
}
    public function filterpayment($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Request for Payment')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')->where('created_by', $userId)
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Request for Payment')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data'));
    }
    public function filterpaymentrejectioned($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Release Rejected')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')->where('created_by', $userId)
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Release Rejected')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data'));
    }
    public function filterpaymentrel($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Initiated')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    }
    else
    {
        $data = PurchasingOrder::with('purchasing_order_items')
        // ->where(function ($query) use ($userId) {
        //     $query->where('purchasing_order.created_by', $userId)
        //         ->orWhere('purchasing_order.created_by', 16);
        // })
        ->where('purchasing_order.status', $status)
        ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
        ->where('vehicles.payment_status', 'Payment Initiated')
        ->select('purchasing_order.*')
        ->groupBy('purchasing_order.id')
        ->get();
    }
        return view('warehouse.index', compact('data'));
    }
    public function filterintentreq($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Request for Payment')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    }
    else
    {
        $data = PurchasingOrder::with('purchasing_order_items')
        // ->where(function ($query) use ($userId) {
        //     $query->where('purchasing_order.created_by', $userId)
        //         ->orWhere('purchasing_order.created_by', 16);
        // })
        ->where('purchasing_order.status', $status)
        ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
        ->where('vehicles.status', 'Request for Payment')
        ->select('purchasing_order.*')
        ->groupBy('purchasing_order.id')
        ->get();
    }
        return view('warehouse.index', compact('data'));
    }
    public function filterpendingrelease($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Initiated Request')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')
            // ->where(function ($query) use ($userId) {
            //     $query->where('purchasing_order.created_by', $userId)
            //         ->orWhere('purchasing_order.created_by', 16);
            // })
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Initiated Request')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data'));
    }
    public function filterpendingdebits($status)
    {
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Release Approved')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')
            // ->where('created_by', $userId)->orWhere('created_by', 16)
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.payment_status', 'Payment Release Approved')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data'));
    }
    public function filterpendingfellow($status)
    {
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
    $data = PurchasingOrder::with('purchasing_order_items')
        ->where('status', $status)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('vehicles')
                ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                ->where(function ($query) {
                    $query->Where('payment_status', 'Payment Completed');
                });
        })
        ->groupBy('purchasing_order.id')
        ->get();
    }
    else
    {
        $data = PurchasingOrder::with('purchasing_order_items')
        // ->where('created_by', $userId)->orWhere('created_by', 16)
        ->where('status', $status)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('vehicles')
                ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                ->where(function ($query) {
                    $query->Where('payment_status', 'Payment Completed');
                });
        })
        ->groupBy('purchasing_order.id')
        ->get();
    }
    return view('warehouse.index', compact('data'));
}
public function filterconfirmation($status)
{
$userId = auth()->user()->id;
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
if ($hasPermission){
$data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->Where('payment_status', 'Vendor confirmed');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    // ->where('created_by', $userId)->orWhere('created_by', 16)
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->Where('payment_status', 'Vendor confirmed');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
return view('warehouse.index', compact('data'));
}
public function paymentinitiation($status)
{
$userId = auth()->user()->id;
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
if ($hasPermission){
$data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->where('status', 'Approved');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    // ->where('created_by', $userId)->orWhere('created_by', 16)
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->where('status', 'Approved');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
return view('warehouse.index', compact('data'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $countries = Country::get();
    $ports = MasterShippingPorts::with('country')->get();
    $useractivities =  New UserActivities();
        $useractivities->activity = "Creating Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $vendors = Supplier::whereHas('vendorCategories', function ($query) {
        $query->where('category', 'Vehicles');
    })->get();
    $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
    $payments = PaymentTerms::get();
    return view('warehouse.create', compact('variants', 'vendors', 'payments','countries','ports'));
}
public function getBrandsAndModelLines(Request $request)
{
    $brands = Brand::all(); // Replace with your actual query to get brands
    $modelLines = MasterModelLines::all(); // Replace with your actual query to get model lines
    return response()->json([
        'brands' => $brands,
        'modelLines' => $modelLines,
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//         dd($request->all());
        $this->validate($request, [
            'payment_term_id' => 'required',
            'po_type' => 'required',
            'vendors_id' => 'required'
        ]);

        DB::beginTransaction();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Store the Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $poDate = $request->input('po_date');
        $po_type = $request->input('po_type');
        $vendors_id = $request->input('vendors_id');
        $purchasingOrder = new PurchasingOrder();
        $purchasingOrder->po_date = $poDate; 
        $purchasingOrder->vendors_id = $vendors_id;
        $purchasingOrder->po_type = $po_type;
        $purchasingOrder->payment_term_id = $request->input('payment_term_id');
        $purchasingOrder->currency = $request->input('currency');
        $purchasingOrder->shippingmethod = $request->input('shippingmethod');
        if($request->po_from != 'DEMAND_PLANNING') {
            $purchasingOrder->shippingcost = $request->input('shippingcost');
        }
        $purchasingOrder->totalcost = $request->input('totalcost');
        $purchasingOrder->pol = $request->input('pol');
        $purchasingOrder->pod = $request->input('pod');
        $purchasingOrder->fd = $request->input('fd');
        $purchasingOrder->status = "Pending Approval";
        $purchasingOrder->created_by = auth()->user()->id;
        $purchasingOrder->is_demand_planning_po = $request->is_demand_planning_po ? true : false;
        if ($request->hasFile('uploadPL')) {
            // Get file with extension
            $fileNameWithExt = $request->file('uploadPL')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('uploadPL')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Move file to public/storage/PL_Documents
            $path = $request->file('uploadPL')->move(public_path('storage/PL_Documents'), $fileNameToStore);
            // Store the path in the database
            $purchasingOrder->pl_file_path = 'storage/PL_Documents/' . $fileNameToStore;
        }
        $purchasingOrder->pl_number = $request->input('pl_number');
        $purchasingOrder->save();
        $purchasingOrderId = $purchasingOrder->id;
        $variantNames = $request->input('variant_id');
        if($variantNames != null)
        {
            $variantIds = Varaint::whereIn('name', $variantNames)->pluck('id')->all();
            $variantsQuantity = array_count_values($variantNames);
            foreach ($variantIds as $variantId) {
                $variant = Varaint::find($variantId);
                $purchasingOrderItem = new PurchasingOrderItems();
                $purchasingOrderItem->variant_id = $variantId;
                $purchasingOrderItem->purchasing_order_id = $purchasingOrderId;
                $purchasingOrderItem->qty = $variantsQuantity[$variant->name];
                $purchasingOrderItem->save();
            }
            $vins = $request->input('vin');
            $ex_colours = $request->input('ex_colour');
            $int_colours = $request->input('int_colour');
            $estimated_arrival = $request->input('estimated_arrival');
            $engine_number = $request->input('engine_number');
            $territory = $request->input('territory');
            $unit_prices = $request->input('unit_prices');
            $count = count($variantNames);
            foreach ($variantNames as $key => $variantName) {
                if ($variantName === null && $key === $count - 1) {
                continue;
                }
                $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
                $vin = $vins[$key];
                $ex_colour = $ex_colours[$key];
                $unit_price = $unit_prices[$key];
                $int_colour = $int_colours[$key];
                $estimation_arrival = $estimated_arrival[$key];
                $engine = $engine_number[$key];
                $vehicle = new Vehicles();
                $vehicle->varaints_id = $variantId;
                $vehicle->vin = $vin;
                $vehicle->ex_colour = $ex_colour;
    //            $vehicle->purchasing_price = $ex_colour;
                $vehicle->int_colour = $int_colour;
                $vehicle->estimation_date = $estimation_arrival;
                $vehicle->engine = $engine;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $vehicle->territory = 'Africa';
                }else{
                    $territorys = $territory[$key];
                    $vehicle->territory = $territorys;
                }
                $vehicle->purchasing_order_id = $purchasingOrderId;
                $vehicle->status = "Not Approved";
                // payment status need to update
                if($request->input('master_model_id')) {
                    $masterModelId = $request->input('master_model_id');
                    $vehicle->model_id = $masterModelId[$key];
                }
                $vehicle->save();
                $vehiclecost = New VehiclePurchasingCost();
                $vehiclecost->currency = $request->input('currency');
                $vehiclecost->unit_price = $unit_price;
                $vehiclecost->vehicles_id = $vehicle->id;
                $vehiclecost->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $purchasinglog = new Purchasinglog();
                $purchasinglog->time = now()->toTimeString();
                $purchasinglog->date = now()->toDateString();
                $purchasinglog->status = 'PO Created';
                $purchasinglog->purchasing_order_id = $purchasingOrderId;
                $purchasinglog->variant = $variantId;
                $purchasinglog->estimation_date = $estimation_arrival;
                $purchasinglog->engine_number = $engine;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $purchasinglog->territory = 'Africa';
                }else{
                    $purchasinglog->territory = $territorys;
                }
                $purchasinglog->ex_colour = $ex_colour;
                $purchasinglog->int_colour = $int_colour;
                $purchasinglog->created_by = auth()->user()->id;
                $purchasinglog->role = Auth::user()->selectedRole;
                $purchasinglog->save();
            }
                $masterModels = $request->master_model_id;

                if($request->po_from == 'DEMAND_PLANNING') {
                    $loiItemsOfPurcahseOrders = $request->approved_loi_ids;
                    foreach($loiItemsOfPurcahseOrders as $key => $loiItemsOfPurchaseOrder) {

                        $approvedLoiItem = ApprovedLetterOfIndentItem::Find($loiItemsOfPurchaseOrder);
                        $pfi = PFI::find($approvedLoiItem->pfi_id);
                        $pfi->status = 'PO Initiated';
                        $pfi->save();

                        if($request->item_quantity_selected[$key] > 0) {
                            $loiPurchaseOrder = new LOIItemPurchaseOrder();
                            $loiPurchaseOrder->approved_loi_id = $loiItemsOfPurchaseOrder;
                            $loiPurchaseOrder->purchase_order_id = $purchasingOrderId;
                            $loiPurchaseOrder->master_model_id = $request->selected_model_ids[$key];
                            $loiPurchaseOrder->quantity = $request->item_quantity_selected[$key] ?? '';
                            $loiPurchaseOrder->save();
                        }
                    }
                    $dealer = $pfi->letterOfIndent->dealers ?? '';
                    $alreadyAddedIds = [];
                    foreach($masterModels as $key => $masterModel)
                    {
                        $model = MasterModel::find($masterModel);
                        $vehicle = Vehicles::where('model_id', $masterModel)->where('purchasing_order_id', $purchasingOrderId)
                                                    ->where('vin', $vins[$key])
                                                    ->whereNull('supplier_inventory_id')
                                                    ->first();

                        $possibleModelIds = MasterModel::where('model', $model->model)
                                            ->where('sfx', $model->sfx)->pluck('id');

                        $inventoryItem = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                            ->whereNull('purchase_order_id')
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->where('supplier_id', $vendors_id)
                            ->whereNotIn('id', $alreadyAddedIds)
                            ->where('whole_sales', $dealer);

                        if($vins[$key]) {
                             $inventoryItem = $inventoryItem->where('chasis', $vins[$key]);
                        }

                        if($inventoryItem->count() > 0) {
                            $inventoryIds = $inventoryItem->pluck('id');
                            $inventory = SupplierInventory::where('pfi_id', $pfi->id)
                                                ->whereIn('id', $inventoryIds);

                            if($inventory->count() > 0) {
                                $inventoryItem = $inventory->first();
                            }else{
                                $inventoryItem = $inventoryItem->first();
                                $inventoryItem->pfi_id = $pfi->id;
                            }

                            $inventoryItem->letter_of_indent_item_id = $request->loi_item_Ids[$key];
                            $inventoryItem->purchase_order_id = $purchasingOrder->id;
                            $inventoryItem->save();

                            // add entry to inventory log table

                            $vehicle->supplier_inventory_id = $inventoryItem->id;
                            $vehicle->save();

                            $alreadyAddedIds[] = $inventoryItem->id;
                        }
                    }
                }
        }
        DB::commit();
        $purchasingordereventsLog = New PurchasingOrderEventsLog();
        $purchasingordereventsLog->event_type = "PO Creation";
        $purchasingordereventsLog->created_by = auth()->user()->id;
        $purchasingordereventsLog->purchasing_order_id = $purchasingOrderId;
        $purchasingordereventsLog->save();
        $supplier_account_id = $request->input('vendors_id');
        $purchasing_order_id = $purchasingOrder->id;
        $updateponum = PurchasingOrder::find($purchasingOrderId);
        $updateponum->po_number = $request->input('po_number');
        $updateponum->save();
        $supplier_exists = SupplierAccount::where('suppliers_id', $vendors_id)->exists();
        if (!$supplier_exists) {
        $supplier_created = New SupplierAccount();
        $supplier_created->opening_balance = 0;
        $supplier_created->current_balance = 0;
        $supplier_created->currency = "AED";
        $supplier_created->suppliers_id = $vendors_id;
        $supplier_created->save();
        }
    return redirect()->route('purchasing-order.index')->with('success', 'PO Created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $countries = Country::get();
        $ports = MasterShippingPorts::with('country')->get();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Show The Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
        $vendorPaymentAdjustments = VendorPaymentAdjustments::where('purchasing_order_id', $id)
        ->where('status', '!=', 'Paid')
        ->select('type', DB::raw('SUM(totalamount) as total_amount', 'amount', DB::raw('SUM(totalamount) as total_adjusted_amount')))
        ->groupBy('type')
        ->get();  
        $totalSum = $vendorPaymentAdjustments->sum('total_amount');
    $alreadypaidamount = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)->where('status', 'Paid')->sum('amount');
    $totalSurcharges = intval(PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
    ->where('change_type', 'Surcharge')
    ->sum('price_change'));
    $totalDiscounts = intval(PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
    ->where('change_type', 'discount')
    ->sum('price_change'));
    $intialamount = DB::table('vehicles')
    ->join('vehicle_purchasing_cost', 'vehicles.id', '=', 'vehicle_purchasing_cost.vehicles_id')
    ->where('vehicles.purchasing_order_id', $id)
    ->where('vehicles.payment_status', 'Payment Initiated Request')
    ->sum('vehicle_purchasing_cost.unit_price');
    $purchasingOrder = PurchasingOrder::with(['polPort', 'podPort', 'fdCountry'])->findOrFail($id);
    $paymentterms = PaymentTerms::findorfail($purchasingOrder->payment_term_id);
    $payments = PaymentTerms::get();
    $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
    $vehiclesdel = Vehicles::onlyTrashed()->where('purchasing_order_id', $id)->get();
    $vendorsname = Supplier::where('id', $purchasingOrder->vendors_id)->value('supplier');
    $vehicleslog = Vehicleslog::whereIn('vehicles_id', $vehicles->pluck('id'))->get();
    $purchasinglog = Purchasinglog::where('purchasing_order_id', $id)->get();
    $vendorstatus = SupplierAccount::where('suppliers_id', $purchasingOrder->vendors_id)
                               ->select('current_balance', 'currency')
                               ->first();
                               if ($vendorstatus) {
                                $vendorBalance = number_format(intval($vendorstatus->current_balance), 0, '.', ',');
                                $vendorCurrency = $vendorstatus->currency;
                                $vendorDisplay = $vendorBalance . ' - ' . $vendorCurrency;
                            } else {
                                $vendorDisplay = 'Account Not Existing';
                            }
        $previousId = PurchasingOrder::where('id', '<', $id)->max('id');
        $nextId = PurchasingOrder::where('id', '>', $id)->min('id');

        $variantCount = 0;
        $pfiVehicleVariants = [];
        $vendors = Supplier::whereHas('vendorCategories', function ($query) {
            $query->where('category', 'Vehicles');
        })->get();
        if($purchasingOrder->LOIPurchasingOrder) {
            $pfi = PFI::findOrFail($purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->id);
            $dealer = $pfi->letterOfIndent->dealers ?? '';
            $pfiVehicleVariants = ApprovedLetterOfIndentItem::select('*', DB::raw('sum(quantity) as quantity'))
                ->where('pfi_id', $pfi->id)
                ->groupBy('letter_of_indent_item_id')
                ->get();

            foreach ($pfiVehicleVariants as $pfiVehicleVariant) {

                $alreadyAddedQuantity = LOIItemPurchaseOrder::where('approved_loi_id', $pfiVehicleVariant->id)
                                            ->sum('quantity');

                $pfiVehicleVariant->quantity = $pfiVehicleVariant->quantity - $alreadyAddedQuantity;

                $masterModel = MasterModel::find($pfiVehicleVariant->letterOfIndentItem->masterModel->id);
                $pfiVehicleVariant->masterModels = MasterModel::where('model', $masterModel->model)
                                                ->where('sfx', $masterModel->sfx)
                                                ->get();

                $possibleModelIds = MasterModel::where('model', $masterModel->model)
                                                ->where('sfx', $masterModel->sfx)->pluck('id');

                $pfiVehicleVariant->inventoryQuantity = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                    ->whereNull('purchase_order_id')
                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                    ->where('supplier_id', $pfi->supplier_id)
                    ->where('whole_sales', $dealer)
                    ->count();
                $variantCount = $variantCount + $pfiVehicleVariant->quantity;
            }
        }
        $purchasingOrderSwiftCopies = PurchasingOrderSwiftCopies::where('purchasing_order_id', $id)->orderBy('created_at', 'desc')
        ->get();
        $purchasedorderevents = PurchasingOrderEventsLog::where('purchasing_order_id', $id)->get();
        return view('purchase.show', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ], compact('purchasingOrder', 'variants', 'vehicles', 'vendorsname', 'vehicleslog',
            'purchasinglog','paymentterms','pfiVehicleVariants','variantCount','vendors', 'payments','vehiclesdel','countries','ports','purchasingOrderSwiftCopies','purchasedorderevents', 'vendorDisplay', 'vendorPaymentAdjustments', 'alreadypaidamount','intialamount','totalSum', 'totalSurcharges', 'totalDiscounts'));
    }
    public function edit($id)
    {
    $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
    $vendorsname = Supplier::where('id', $purchasingOrder->vendors_id)->value('supplier');
    return view('warehouse.edit', compact('purchasingOrder', 'variants', 'vehicles', 'vendorsname'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
//     dd($request->all());
        DB::beginTransaction();
        $useractivities =  New UserActivities();
            $useractivities->activity = "Update the Purchased order details";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
        $purchasingOrderId = $id;
        $variantNames = $request->input('variant_id');
        if($variantNames != null)
            {
            $variantIds = Varaint::whereIn('name', $variantNames)->pluck('id')->all();
            $variantsQuantity = array_count_values($variantNames);
            foreach ($variantIds as $variantId) {
                $variant = Varaint::find($variantId);
                $purchasingOrderItem = new PurchasingOrderItems();
                $variantQuantity = $variantsQuantity[$variant->name];
                    $IsExistpurchasingOrderItem = PurchasingOrderItems::where('purchasing_order_id', $purchasingOrderId)
                                                     ->where('variant_id', $variantId)->first();
                    if($IsExistpurchasingOrderItem) {
                        $purchasingOrderItem =  $IsExistpurchasingOrderItem;
                        $variantQuantity = $IsExistpurchasingOrderItem->qty + $variantQuantity;
                    }
                $purchasingOrderItem->qty = $variantQuantity;
                $purchasingOrderItem->variant_id = $variantId;
                $purchasingOrderItem->purchasing_order_id = $purchasingOrderId;

                $purchasingOrderItem->save();
            }
            $vins = $request->input('vin');
            $ex_colours = $request->input('ex_colour');
            $int_colours = $request->input('int_colour');
            $estimated_arrival = $request->input('estimated_arrival');
            $territory = $request->input('territory');
            $engine_number = $request->input('engine_number');
            $unit_prices = $request->input('unit_prices');
            $count = count($variantNames);
            foreach ($variantNames as $key => $variantName) {
                if ($variantName === null && $key === $count - 1) {
                continue;
                }
                $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
                $ex_colour = $ex_colours[$key];
                $int_colour = $int_colours[$key];
                $engine = $engine_number[$key];
                $estimated_arrivals = $estimated_arrival[$key];
                $unit_price = $unit_prices[$key];
                $vin = $vins[$key];
                $vehicle = new Vehicles();
                $vehicle->varaints_id = $variantId;
                $vehicle->vin = $vin;
                $vehicle->ex_colour = $ex_colour;
                $vehicle->int_colour = $int_colour;
                $vehicle->estimation_date = $estimated_arrivals;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $vehicle->territory = 'Africa';
                }else{
                    $territorys = $territory[$key];
                    $vehicle->territory = $territorys;
                }
                if($request->input('master_model_id')) {
                    $masterModelId = $request->input('master_model_id');
                    $vehicle->model_id = $masterModelId[$key];
                }
                $vehicle->purchasing_order_id = $purchasingOrderId;
                $vehicle->status = "New Vehicles";
                $vehicle->save();
                $purchasingOrdertotal = PurchasingOrder::find($purchasingOrderId);
                $purchasingOrdertotal->totalcost = $purchasingOrdertotal->totalcost + $unit_price;
                $purchasingOrdertotal->save();
                $vehiclecost = New VehiclePurchasingCost();
                $vehiclecost->currency = $request->input('currency');
                $vehiclecost->unit_price = $unit_price;
                $vehiclecost->vehicles_id = $vehicle->id;
                $vehiclecost->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $purchasinglog = new Purchasinglog();
                $purchasinglog->time = now()->toTimeString();
                $purchasinglog->date = now()->toDateString();
                $purchasinglog->status = 'Adding New Vehicle';
                $purchasinglog->purchasing_order_id = $purchasingOrderId;
                $purchasinglog->variant = $variantId;
                $purchasinglog->estimation_date = $estimated_arrivals;
                $purchasinglog->engine_number = $engine;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $vehicle->territory = 'Africa';
                }else{
                    $purchasinglog->territory = $territorys;
                }
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

    //         Demand planning PO
                $masterModels = $request->master_model_id;

                if($request->po_from == 'DEMAND_PLANNING') {
                    $loiItemsOfPurcahseOrders = $request->approved_loi_ids;
                    foreach($loiItemsOfPurcahseOrders as $key => $loiItemsOfPurchaseOrder) {

                        $approvedLoiItem = ApprovedLetterOfIndentItem::Find($loiItemsOfPurchaseOrder);
                        $pfi = PFI::find($approvedLoiItem->pfi_id);
                        $pfi->status = 'PO Initiated';
                        $pfi->save();

                        if($request->item_quantity_selected[$key] > 0) {
                            $loiPurchaseOrder = new LOIItemPurchaseOrder();
                            $loiPurchaseOrder->approved_loi_id = $loiItemsOfPurchaseOrder;
                            $loiPurchaseOrder->purchase_order_id = $purchasingOrderId;
                            $loiPurchaseOrder->master_model_id = $request->selected_model_ids[$key];
                            $loiPurchaseOrder->quantity = $request->item_quantity_selected[$key] ?? '';
                            $loiPurchaseOrder->save();
                        }
                    }
                    $dealer = $pfi->letterOfIndent->dealers ?? '';
                    $alreadyAddedIds = [];
                    foreach($masterModels as $key => $masterModel)
                    {
                        $model = MasterModel::find($masterModel);
                        $possibleModelIds = MasterModel::where('model', $model->model)
                                            ->where('sfx', $model->sfx)->pluck('id');
                        $vehicle = Vehicles::where('model_id', $masterModel)
                                                    ->where('purchasing_order_id', $purchasingOrderId)
                                                    ->where('vin', $vins[$key])
                                                    ->whereNull('supplier_inventory_id')
                                                    ->first();


                        $inventoryItem = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                            ->whereNull('purchase_order_id')
                            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                            ->whereNotIn('id', $alreadyAddedIds)
                            ->where('supplier_id', $purchasingOrder->vendors_id)
                            ->where('whole_sales', $dealer);

                        if($vins[$key]) {
                            $inventoryItem = $inventoryItem->where('chasis', $vins[$key]);
                        }

                        if($inventoryItem->count() > 0) {
                            $inventoryIds = $inventoryItem->pluck('id');
                            $inventory = SupplierInventory::where('pfi_id', $pfi->id)
                                                        ->whereIn('id', $inventoryIds);
                            if($inventory->count() > 0) {
                                $inventoryItem = $inventory->first();

                            }else{
                                $inventoryItem = $inventoryItem->first();
                                $inventoryItem->pfi_id = $pfi->id;
                            }
                            $inventoryItem->letter_of_indent_item_id = $request->loi_item_Ids[$key];
                            $inventoryItem->purchase_order_id = $purchasingOrder->id;
                            $inventoryItem->save();

                            $vehicle->supplier_inventory_id = $inventoryItem->id;
                            $vehicle->save();

                            $alreadyAddedIds[] = $inventoryItem->id;
                        }
                    }
                }

        }
        DB::commit();
        foreach ($variantsQuantity as $variant => $quantity) {
            $description = $variant . ' with ' . $quantity . ' qty';
            $purchasingordereventsLog = new PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "Add New Vehicles";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $purchasingOrderId;
            $purchasingordereventsLog->description = $description;
            $purchasingordereventsLog->save();
        }
    return back()->with('success', 'Added Vehicles In PO successfully!');
    }
    public function deletes($id)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Delete the Purchased Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
        ->where('payment_status', 'Payment Completed')
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
    $useractivities =  New UserActivities();
        $useractivities->activity = "View details of the Purchased Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $varaint = Varaint::get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $data = Vehicles::where('purchasing_order_id', $id)->where('status', '!=', 'cancel')->get();
    $vendorsname = Supplier::where('id', $purchasingOrder->vendors_id)->value('supplier');
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
    public function checkcreatevinsinside(Request $request)
    {
        $vinValues = $request->input('vins');
        $po = $request->input('po');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->whereNot('purchasing_order_id', $po)->pluck('vin')->toArray();
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
                // $vehicle->status = 'New Changes'; // Set the vehicle status
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
                    if ($field == 'int_colour') {
                        $newfield = "Interior Colour";
                        $oldval = ColorCode::find($change['old_value']);
                        $oldvalue = $oldval ? $oldval->name : "";
                        $newval = ColorCode::find($change['new_value']);
                        $namevalue = $newval->name;
                    } elseif ($field == 'ex_colour') {
                        $newfield = "Exterior Colour";
                        $oldval = ColorCode::find($change['old_value']);
                        $oldvalue = $oldval ? $oldval->name : "";
                        $newval = ColorCode::find($change['new_value']);
                        $namevalue = $newval->name;
                    } elseif ($field == 'engine') {
                        $newfield = "Engine Number";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } elseif ($field == 'vin') {
                        $newfield = "VIN";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } elseif ($field == 'territory') {
                        $newfield = "Territory";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } elseif ($field == 'estimation_date') {
                        $newfield = "Estimation Date";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } else {
                        $newfield = $field;
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    }
                    $description = "Vehicle reference is $vehicleId change the $newfield from $oldvalue to $namevalue";
                    $purchasingordereventsLog = new PurchasingOrderEventsLog();
                    $purchasingordereventsLog->event_type = "Changes into Vehicle date";
                    $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = $newfield;
                    $purchasingordereventsLog->old_value = $oldvalue;
                    $purchasingordereventsLog->new_value = $namevalue;
                    $purchasingordereventsLog->description = $description;
                    $purchasingordereventsLog->save();
                }
                $purchasingOrderId = $vehicle->purchasing_order_id;
                $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
                if ($purchasingOrder) {
                    info($fieldName);
//                    check the po is under demand planning

                    $loiPurchasingOrder = LOIItemPurchaseOrder::where('purchase_order_id', $purchasingOrderId)->first();
                       if($loiPurchasingOrder) {
                           $supplierInventory = SupplierInventory::find($vehicle->supplier_inventory_id);
                           if($supplierInventory) {
                               if($fieldName == 'vin') {
                                   $supplierInventory->chasis = $fieldValue;
                               }
                               if($fieldName == 'estimation_date') {
                                   $supplierInventory->eta_import =  \Illuminate\Support\Carbon::parse($fieldValue)->format('Y-m-d');
                               }
                               if($fieldName == 'int_colour') {
                                   $supplierInventory->interior_color_code_id = $fieldValue ?? '';
                               }
                               if($fieldName == 'ex_colour') {
                                   $supplierInventory->exterior_color_code_id = $fieldValue ?? '';
                               }
                               if($fieldName == 'engine') {
                                   $supplierInventory->engine_number = $fieldValue ?? '';
                               }
                               $action = str_replace('_', ' ', $fieldName) ." updated";
                               (new SupplierInventoryController)->inventoryLog($action, $supplierInventory->id);

                               $supplierInventory->save();
                           }
                       }

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
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update Purchasing Order Status";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
            if ($vehicle->status == 'New Changes' || $vehicle->status == 'Not Approved' || $vehicle->status == 'New Vehicles') {
            if($purchasingOrder->po_type === "Payment Adjustment")
            {
                $vehicle->status = 'Payment Completed';
                $vehicle->payment_status = 'Payment Completed';
            }
            else{
                $vehicle->status = $status;
            }
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
            $purchasingordereventsLog = new PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "PO Approved";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $id;
            $purchasingordereventsLog->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
    public function confirmPayment($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Purchased order status to payment confirm";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Not Paid";
                $vehicleslog->new_value = "Request for Initiate Payment";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Request for Payment";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Vehicle Status";
                    $purchasingordereventsLog->old_value = "Not Paid";
                    $purchasingordereventsLog->new_value = "Request for Initiate Payment";
                    $purchasingordereventsLog->description = "PO Creator Request the Payment to the Againt of the Vehicle Ref $id";
                    $purchasingordereventsLog->save();
            return redirect()->back()->with('success', 'Payment confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function cancel(Request $request, $id)
    {
        $vehicle = Vehicles::findOrFail($id);
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-edit');
        if ($vehicle->status == 'Approved' || $vehicle->status == 'Request for Payment' || $vehicle->status == 'Payment In-Process'|| $vehicle->status == 'Payment Requested') {
            if($hasPermission)
            {
                $purchasinglog = new Purchasinglog();
                $purchasinglog->time = now()->toTimeString();
                $purchasinglog->date = now()->toDateString();
                $purchasinglog->status = 'Vehicle Cancel';
                $purchasinglog->role = Auth::user()->selectedRole;
                $purchasinglog->purchasing_order_id = $vehicle->purchasing_order_id;
                $purchasinglog->variant = $vehicle->varaints_id;
                $purchasinglog->estimation_date = $vehicle->estimation_date;
                $purchasinglog->territory = $vehicle->territory;
                $purchasinglog->int_colour = $vehicle->int_colour;
                $purchasinglog->ex_colour = $vehicle->ex_colour;
                $purchasinglog->created_by = auth()->user()->id;
                $purchasinglog->save();
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = now()->toTimeString();
                $vehicleslog->date = now()->toDateString();
                $vehicleslog->status = 'Vehicle Cancel';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Status";
                $vehicleslog->old_value = $vehicle->status;
                $vehicleslog->new_value = 'Vehicle Cancel';
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Vehicle Cancel";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Cancel Vehicle";
                    $purchasingordereventsLog->new_value = "Vehicle Cancel";
                    $purchasingordereventsLog->description = "Vehicle Procurement Manager Cancel the Vehicle $id";
                    $purchasingordereventsLog->save();
                $updateqty = PurchasingOrderItems::where('variant_id', $vehicle->varaints_id)->where('purchasing_order_id', $vehicle->purchasing_order_id)->first();
                if($updateqty)
                {
                    $updateqty->qty = intval($updateqty->qty) - 1;
                    $updateqty->save();
                }
                $updateprice = VehiclePurchasingCost::where('vehicles_id', $id)->first();
                if($updateprice)
                {
                $updatetotal = PurchasingOrder::find($vehicle->purchasing_order_id);
                $updatetotal->totalcost = $updatetotal->totalcost - $updateprice->unit_price;
                $updatetotal->save();
                }
                if($vehicle->model_id) {
                    $masterModel = MasterModel::find($vehicle->model_id);
                    $possibleModelIds = MasterModel::where('model', $masterModel->model)
                        ->where('sfx', $masterModel->sfx)->pluck('id');
                    $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
                        ->whereIn('master_model_id', $possibleModelIds)
                        ->first();
                    $inventoryItem->purchase_order_id = NULL;
                    $inventoryItem->pfi_id = NULL;
                    $inventoryItem->letter_of_indent_item_id  = NULL;
                    $inventoryItem->save();

                    $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                                                                ->where('master_model_id', $vehicle->model_id)
                                                                ->first();
                    if($loiPurchaseOrder) {
                        $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity - 1;
                        $loiPurchaseOrder->save();
                    }

                }
            $vehicle->procurement_vehicle_remarks = $request->input('remarks');
            $vehicle->save();
            $vehicle->delete();
            }
            else
            {
            $vehicle->status = 'Request for Cancel';
            $vehicle->procurement_vehicle_remarks = $request->input('remarks');
            $vehicle->save();
            $purchasedorders = PurchasingOrder::find($vehicle->purchasing_order_id);
            $purchasedorders->status = 'Pending Approval';
            $purchasedorders->save();
            $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Cancel Request";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Vehicle Cancel Request";
                    $purchasingordereventsLog->new_value = "Cancel Request";
                    $purchasingordereventsLog->description = "Vehicle Executive Send to Cancel the Vehicle $id";
                    $purchasingordereventsLog->save();
            }
        }
        else
        {
        $purchasinglog = new Purchasinglog();
        $purchasinglog->time = now()->toTimeString();
        $purchasinglog->date = now()->toDateString();
        $purchasinglog->status = 'Vehicle Cancel';
        $purchasinglog->role = Auth::user()->selectedRole;
        $purchasinglog->purchasing_order_id = $vehicle->purchasing_order_id;
        $purchasinglog->variant = $vehicle->varaints_id;
        $purchasinglog->estimation_date = $vehicle->estimation_date;
        $purchasinglog->territory = $vehicle->territory;
        $purchasinglog->int_colour = $vehicle->int_colour;
        $purchasinglog->ex_colour = $vehicle->ex_colour;
        $purchasinglog->created_by = auth()->user()->id;
        $purchasinglog->save();
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = now()->toTimeString();
        $vehicleslog->date = now()->toDateString();
        $vehicleslog->status = 'Vehicle Cancel';
        $vehicleslog->vehicles_id = $id;
        $vehicleslog->field = "Status";
        $vehicleslog->old_value = $vehicle->status;
        $vehicleslog->new_value = 'Vehicle Cancel';
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
        $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Vehicle Cancel";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Cancel Vehicle";
                    $purchasingordereventsLog->new_value = "Vehicle Cancel";
                    $purchasingordereventsLog->description = "Vehicle Procurement Manager Cancel the Vehicle $id";
                    $purchasingordereventsLog->save();
        $updateqty = PurchasingOrderItems::where('variant_id', $vehicle->varaints_id)->where('purchasing_order_id', $vehicle->purchasing_order_id)->first();
        if($updateqty)
        {
            $updateqty->qty = intval($updateqty->qty) - 1;
            $updateqty->save();
        }
        $updateprice = VehiclePurchasingCost::where('vehicles_id', $id)->first();
        if($updateprice)
        {
        $updatetotal = PurchasingOrder::find($vehicle->purchasing_order_id);
        $updatetotal->totalcost = $updatetotal->totalcost - $updateprice->unit_price;
        $updatetotal->save();
        }
        if($vehicle->model_id) {
                $masterModel = MasterModel::find($vehicle->model_id);
                $possibleModelIds = MasterModel::where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx)->pluck('id');
                $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->whereIn('master_model_id', $possibleModelIds)
                    ->first();
                $inventoryItem->purchase_order_id = NULL;
                $inventoryItem->pfi_id = NULL;
                $inventoryItem->letter_of_indent_item_id  = NULL;
                $inventoryItem->save();

                $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                                                            ->where('master_model_id', $vehicle->model_id)
                                                            ->first();
                if($loiPurchaseOrder) {
                    $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity - 1;
                    $loiPurchaseOrder->save();
                }

            }
        $vehicle->procurement_vehicle_remarks = $request->input('remarks');
        $vehicle->save();
        $vehicle->delete();
        }
        return redirect()->back()->with('success', 'Vehicle cancellation request submitted successfully.');
    }
    public function rejecteds($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Purchased order status to Rejected By BOD";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Pending Approval";
                $vehicleslog->new_value = "Rejected By BOD";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();

            if($vehicle->model_id) {
                $masterModel = MasterModel::find($vehicle->model_id);
                $possibleModelIds = MasterModel::where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx)->pluck('id');
                $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->whereIn('master_model_id', $possibleModelIds)
                    ->first();

                $inventoryItem->purchase_order_id = NULL;
                $inventoryItem->pfi_id = NULL;
                $inventoryItem->letter_of_indent_item_id  = NULL;
                $inventoryItem->save();

                $purchaseOrderItem = PurchasingOrderItems::where('purchasing_order_id', $vehicle->purchasing_order_id)
                    ->where('variant_id', $vehicle->varaints_id)->first();

                if($purchaseOrderItem) {
                    $purchaseOrderItem->qty = $purchaseOrderItem->qty - 1;
                    $purchaseOrderItem->save();
                }

                $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->where('master_model_id', $vehicle->model_id)
                    ->first();

                $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity - 1;
                $loiPurchaseOrder->save();
            }
            return redirect()->back()->with('success', 'Payment confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function unrejecteds($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Purchased order status to Rejected By BOD";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Rejected By BOD";
                $vehicleslog->new_value = "Pending Approval";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            if($vehicle->model_id) {
                $masterModel = MasterModel::find($vehicle->model_id);
                $possibleModelIds = MasterModel::where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx)->pluck('id');

                $purchasingOrder = PurchasingOrder::findOrFail($vehicle->purchasing_order_id);
                $dealer = $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->letterOfIndent->dealers ?? '';
                $pfi_id = $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->id ?? '';
                $letterOfIndentId = $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->letter_of_indent_id ?? '';

                $inventoryItem = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                    ->whereNull('purchase_order_id')
                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                    ->where('supplier_id', $purchasingOrder->vendors_id)
                    ->where('whole_sales', $dealer);

                if($vehicle->vin) {
                    $inventoryItem = $inventoryItem->where('chasis', $vehicle->vin);
                }

                if($inventoryItem->count() > 0) {
                    $inventoryIds = $inventoryItem->pluck('id');
                    $inventory = SupplierInventory::where('pfi_id', $pfi_id)
                        ->whereIn('id', $inventoryIds);
                    if($inventory->count() > 0) {
                        $inventoryItem = $inventory->first();

                    }else{
                        $inventoryItem = $inventoryItem->first();
                        $inventoryItem->pfi_id = $pfi_id;
                    }

                    $loiItem = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndentId)
                                                ->whereIn('master_model_id', $possibleModelIds)->first();
                    if($loiItem) {
                        $inventoryItem->letter_of_indent_item_id = $loiItem->id ?? '';
                    }
                    $inventoryItem->purchase_order_id = $purchasingOrder->id;
                    $inventoryItem->save();
                }
                $purchaseOrderItem = PurchasingOrderItems::where('purchasing_order_id', $vehicle->purchasing_order_id)
                                                             ->where('variant_id', $vehicle->varaints_id)->first();
                if($purchaseOrderItem) {
                    $purchaseOrderItem->qty = $purchaseOrderItem->qty + 1;
                    $purchaseOrderItem->save();
                }

                $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->where('master_model_id', $vehicle->model_id)
                    ->first();
                $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity + 1;
                $loiPurchaseOrder->save();
            }
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
        $vehiclesLog->field = "Vehicle Status";
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
        $vehicle->payment_status = 'Payment Initiated Request';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Request';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Request for Initiate Payment";
            $vehicleslog->new_value = "Payment Initiated Request";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $totalCost = VehiclePurchasingCost::where('vehicles_id', $vehicle->id)->value('unit_price');
            $paymentinti = New PurchasedOrderPaidAmounts();
            $paymentinti->amount = $totalCost;
            $paymentinti->purchasing_order_id = $vehicle->purchasing_order_id;
            $paymentinti->created_by = auth()->user()->id;
            $paymentinti->status = "Request For Payment";
            $paymentinti->save();
        return redirect()->back()->with('success', 'Payment Initiated Request confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleaserejected($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Rejected';
        $vehicle->payment_status = 'Payment Initiate Request Rejected';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiate Request Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Initiated Request";
            $vehicleslog->new_value = "Payment Initiate Request Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiate Request Rejected confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleaseconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Initiate Request Approved';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiate Request Approved';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiated Request";
            $vehicleslog->new_value = "Payment Initiate Request Approved";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiate Request Approved confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirm($id)
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
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiate Request Approved";
            $vehicleslog->new_value = "Payment Initiated";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiated confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleasesconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        DB::beginTransaction();
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Release Approved';
        $vehicle->procurement_vehicle_remarks = null;
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Release Approved';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiated";
            $vehicleslog->new_value = "Payment Release Approved";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            if($vehicle->model_id) {
                // get the loi item and update the utilization quantity
                $approvedIds = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->pluck('approved_loi_id');

                $loiItemIds = ApprovedLetterOfIndentItem::whereIn('id', $approvedIds)->pluck('letter_of_indent_item_id');
                $possibleIds = MasterModel::where('model', $vehicle->masterModel->model)
                    ->where('sfx', $vehicle->masterModel->sfx)->pluck('id')->toArray();
                foreach ($loiItemIds as $loiItemId) {
                    $item = LetterOfIndentItem::find($loiItemId);
                    if(in_array($item->master_model_id, $possibleIds)) {
                        if($item->utilized_quantity < $item->approved_quantity) {
                            $item->utilized_quantity = $item->utilized_quantity + 1;
                            $item->save();
                            break;
                        }
                    }
                }
            }
            DB::commit();

        return redirect()->back()->with('success', 'Payment Payment Release Approved confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleasesrejected(Request $request, $id)
{

    info($id);
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Rejected';
        $vehicle->payment_status = 'Payment Release Rejected';
        $vehicle->procurement_vehicle_remarks = $request->input('remarks');
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Release Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiated";
            $vehicleslog->new_value = "Payment Release Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            return response()->json(['success' => 'Payment Release Rejected confirmed. Vehicle status updated.']);
    }
    return response()->json(['error' => 'Vehicle not found.'], 404);
}
public function paymentrelconfirmdebited(Request $request, $id)
{
    $vehicle = Vehicles::find($id);
    $vehicleCount = $vehicle->count();
           if ($request->hasFile('paymentFile')) {
            $file = $request->file('paymentFile');
            $fileNameToStore = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/swift_copies'), $fileNameToStore);            
            $latestBatch = DB::table('purchasing_order_swift_copies')
                ->where('purchasing_order_id', $vehicle->purchasing_order_id)
                ->orderBy('created_at', 'desc')
                ->first();
            $batchNo = $latestBatch ? $latestBatch->batch_no + 1 : 1;
            $swiftcopy = new PurchasingOrderSwiftCopies();
            $swiftcopy->purchasing_order_id = $vehicle->purchasing_order_id;
            $swiftcopy->uploaded_by = auth()->user()->id;
            $swiftcopy->number_of_vehicles = $vehicleCount;
            $swiftcopy->batch_no = $batchNo;
            $swiftcopy->file_path = 'storage/swift_copies/' . $fileNameToStore;
            $swiftcopy->save();
        }
    if ($vehicle) {
        DB::beginTransaction();
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
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Release Approved";
            $vehicleslog->new_value = "Payment Completed";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $paymentlogs = new PaymentLog();
                $paymentlogs->date = $currentDateTime->toDateString();
                $paymentlogs->vehicle_id = $vehicle->id;
                $paymentlogs->created_by = auth()->user()->id;
                $paymentlogs->save();

                DB::commit();
        return redirect()->back()->with('success', 'Payment Payment Completed confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirmvendors($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Vendor Confirmed';
        $vehicle->payment_status = 'Vendor Confirmed';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Vendor Confirmed';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Completed";
            $vehicleslog->new_value = "Vendor Confirmed";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
//            if($vehicle->master_model_id) {
//                $masterModel = MasterModel::find($vehicle->master_model_id);
//                $similarModelIds = MasterModel::where('model', $masterModel->model)
//                    ->where('steering', $masterModel->steering)
//                    ->where('sfx', $masterModel->sfx)
//                    ->where('model_year', $masterModel->model_year)
//                    ->pluck('id')->toArray();
//                // find the supplier and dealer
//               $supplier_id = $vehicle->purchasingOrder->LOIPurchasingOrder->approvedLOI->letterOfIndent->supplier_id ?? '';
//               $dealer = $vehicle->purchasingOrder->LOIPurchasingOrder->approvedLOI->letterOfIndent->dealers ?? '';
//              // dd($supplier_id);
//                // check the eta import date update time
//               $supplierInventory = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                   ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
//                   ->where('supplier_id', $supplier_id)
//                   ->where('whole_sales', $dealer)
//                   ->whereIn('master_model_id', $similarModelIds)
//                    ->whereNull('delivery_note')
//                   ->first();
////               info($supplierInventory->id);
//               if($supplierInventory) {
//                   $supplierInventory->veh_status = SupplierInventory::VEH_STATUS_VENDOR_CONFIRMED;
//                   $supplierInventory->save();
//               }
//
//            }

        return redirect()->back()->with('success', 'Vendor Confirmed confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirmincoming($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Incoming Stock';
        $vehicle->payment_status = 'Incoming Stock';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Incoming Stock';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Vendor Confirmed";
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
        ->where('payment_status', 'Payment Initiated Request')
        ->where('purchasing_order_id', $id)
        ->get();
    foreach ($vehicles as $vehicle) {
    if ($status == 'Approved') {
            $paymentStatus = 'Payment Initiate Request Approved';
        } elseif ($status == 'Rejected') {
            $paymentStatus = 'Payment Initiate Request Rejected';
        }
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['payment_status' => $paymentStatus]);
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Payment Initiated Request Status';
        $vehicleslog->vehicles_id = $vehicle->id;
        $vehicleslog->field = 'Payment Status';
        $vehicleslog->old_value = 'Payment Initiated Request';
        $vehicleslog->new_value = $paymentStatus;
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
    }
    return redirect()->back()->with('success', 'Payment Initiated Request confirmed. Vehicle status updated.');
}
public function purchasingallupdateStatusrel(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $remarks = $request->input('remarks', null);
    $vehicles = DB::table('vehicles')
        ->where('payment_status', 'Payment Initiated')
        ->where('purchasing_order_id', $id)
        ->get();
    if ($status == 'Approved') {
        $PurchasingOrder = PurchasingOrder::find($id);
        $supplieracc = SupplierAccount::where('suppliers_id', $PurchasingOrder->vendors_id)->first();
        if ($supplieracc) {
            $paymentad = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)
                ->where('status', 'Suggested Payment')
                ->sum('amount');
            $supplieracc->current_balance += $paymentad;
            $supplieracc->save();
            PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)
                ->where('status', 'Suggested Payment')
                ->update(['status' => 'Paid']);
            VendorPaymentAdjustments::where('purchasing_order_id', $id)
                ->where('status', 'pending')
                ->update(['status' => 'Paid']);
            $supplieraccount = new SupplierAccountTransaction();
            $supplieraccount->transaction_type = "Debit";
            $supplieraccount->purchasing_order_id = $id;
            $supplieraccount->supplier_account_id = $supplieracc->id;
            $supplieraccount->created_by = auth()->user()->id;
            $supplieraccount->account_currency = $PurchasingOrder->currency;
            $supplieraccount->transaction_amount = $paymentad;
            $supplieraccount->save();
        }
        } 
    foreach ($vehicles as $vehicle) {
        if ($status == 'Approved') {
                $paymentStatus = 'Payment Release Approved';
                $updateData = ['payment_status' => $paymentStatus,
                'procurement_vehicle_remarks' => null
            ];
            } elseif ($status == 'Rejected') {
                $paymentStatus = 'Payment Release Rejected';
                $updateData = [
                    'payment_status' => $paymentStatus,
                    'procurement_vehicle_remarks' => $remarks
                ];
            }
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update($updateData);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Status';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = 'Payment Status';
            $vehicleslog->old_value = 'Payment Initiated';
            $vehicleslog->new_value = $paymentStatus;
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            if($vehicle->model_id) {
                $approvedIds = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                                    ->pluck('approved_loi_id');

                $loiItemIds = ApprovedLetterOfIndentItem::whereIn('id', $approvedIds)->pluck('letter_of_indent_item_id');
                info($loiItemIds);
                $masterModel = MasterModel::find($vehicle->model_id);
                $possibleIds = MasterModel::where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();
                info($possibleIds);

                foreach ($loiItemIds as $loiItemId) {
                    $item = LetterOfIndentItem::find($loiItemId);
                    info($item);
                    if(in_array($item->master_model_id, $possibleIds)) {
                        info("master model id including li item");
                        if($item->utilized_quantity < $item->approved_quantity) {
                            info("approved_quantity < utilized_quantity");
                            $item->utilized_quantity = $item->utilized_quantity + 1;
                            $item->save();
                            break;
                        }
                    }
                }
            }
    }
    return redirect()->back()->with('success', 'Payment Status Updated');
}
public function allpaymentreqss(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $vehicles = DB::table('vehicles')
    ->where('purchasing_order_id', $id)
    ->where('status', 'Approved')
    ->where('payment_status', '')
    ->get();
    foreach ($vehicles as $vehicle) {
        $status = 'Request for Payment';
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['status' => $status]);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Request for Payment';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Not Paid";
                $vehicleslog->new_value = "Request for Initiate Payment";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
    }
     return redirect()->back()->with('success', 'Payment Status Updated');
}
public function allpaymentreqssfin(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    if($status == "Approved")
    {
    $vehicles = DB::table('vehicles')
    ->where('purchasing_order_id', $id)
    ->where('status', 'Request for Payment')
    ->where('payment_status', '')
    ->get();
    $totalCost = 0;
    foreach ($vehicles as $vehicle) {
        $status = 'Payment Requested';
        $payment_status = 'Payment Initiated Request';
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['status' => $status, 'payment_status' => $payment_status]);
            $vehicleCost = VehiclePurchasingCost::where('vehicles_id', $vehicle->id)->value('unit_price');
            $totalCost += $vehicleCost;
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Request';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Request for Initiate Payment";
            $vehicleslog->new_value = "Payment Initiated Request";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
             }
            $paymentinti = New PurchasedOrderPaidAmounts();
            $paymentinti->amount = $totalCost;
            $paymentinti->purchasing_order_id = $id;
            $paymentinti->created_by = auth()->user()->id;
            $paymentinti->status = "Request For Payment";
            $paymentinti->save();
            }
            else
            {
                $vehicles = DB::table('vehicles')
                ->where('purchasing_order_id', $id)
                ->where('status', 'Request for Payment')
                ->where('payment_status', '')
                ->get();
                foreach ($vehicles as $vehicle) {
                    $status = 'Approved';
                    $payment_status = Null;
                    DB::table('vehicles')
                        ->where('id', $vehicle->id)
                        ->update(['status' => $status, 'payment_status' => $payment_status]);
                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        $currentDateTime = Carbon::now($dubaiTimeZone);
                        $vehicleslog = new Vehicleslog();
                        $vehicleslog->time = $currentDateTime->toTimeString();
                        $vehicleslog->date = $currentDateTime->toDateString();
                        $vehicleslog->status = 'Payment Initiated Request Rejected';
                        $vehicleslog->vehicles_id = $vehicle->id;
                        $vehicleslog->field = "Vehicle Status, Payment Status";
                        $vehicleslog->old_value = "Request for Initiate Payment";
                        $vehicleslog->new_value = "Payment Initiated Request Rejected";
                        $vehicleslog->created_by = auth()->user()->id;
                        $vehicleslog->role = Auth::user()->selectedRole;
                        $vehicleslog->save();
                         }   
            }
     return redirect()->back()->with('success', 'Payment Status Updated');
}
public function allpaymentreqssfinpay(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $vehicles = DB::table('vehicles')
    ->where('purchasing_order_id', $id)
    ->where('status', 'Payment Requested')
    ->where('payment_status', 'Payment Initiated Request')
    ->get();
    foreach ($vehicles as $vehicle) {
        $status = 'Payment Requested';
        $payment_status = 'Payment Initiated';
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['status' => $status, 'payment_status' => $payment_status]);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Payment Initiated';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "Vehicle Status, Payment Status";
                $vehicleslog->old_value = "Payment Initiate Request Approved";
                $vehicleslog->new_value = "Payment Initiated";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            }
            $purchasedorder = PurchasingOrder::where('id', $id)->first();
            $selectedOption =  $request->input('selectedOption');
            $adjustmentAmount =  $request->input('adjustmentAmount');
            $remainingAmount = $request->input('remainingAmount');
            $intialamount = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)->where('status', 'Request For Payment')->sum('amount');
            if($selectedOption == 'adjustment')
            {
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $adjustmentAmount;
                $VendorPaymentAdjustments->type = "Adjustment";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $adjustmentAmount + $remainingAmount;
                $VendorPaymentAdjustments->remaining_amount = $remainingAmount;
                $VendorPaymentAdjustments->save();
                $totalcost = $intialamount;
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount + $remainingAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
            }
            elseif($selectedOption == 'payBalance')
            {
                $supplier = SupplierAccount::where('suppliers_id', $purchasedorder->vendors_id)->first();
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $adjustmentAmount - $intialamount;
                $VendorPaymentAdjustments->type = "Pay Balance";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $adjustmentAmount;
                $VendorPaymentAdjustments->remaining_amount = $intialamount;
                $VendorPaymentAdjustments->save();
                $totalcost = $intialamount;
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
            }
            elseif($selectedOption == 'partialpayment')
            {
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $intialamount;
                $VendorPaymentAdjustments->type = "Partial Payment";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $adjustmentAmount;
                $VendorPaymentAdjustments->remaining_amount = $intialamount - $adjustmentAmount;
                $VendorPaymentAdjustments->save();
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
                $totalcost = $intialamount;
            }
            else
            {
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $adjustmentAmount;
                $VendorPaymentAdjustments->type = "No Adjustment";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $intialamount;
                $VendorPaymentAdjustments->save();
                $totalcost = $intialamount;
                $adjustmentAmount = $intialamount;
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
            }
            PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)->where('status', 'Request For Payment')->update(['status' => 'Initiated Payment']);
            $currency = $purchasedorder->currency;
            $supplieraccountchange = SupplierAccount::where('suppliers_id', $purchasedorder->vendors_id)->first();
            if (!$supplieraccountchange) {
            $supplieraccountchange = new SupplierAccount();
            $supplieraccountchange->suppliers_id = $purchasedorder->vendors_id;
            $supplieraccountchange->current_balance -= $totalcost;
            $supplieraccountchange->currency = "AED";
            $supplieraccountchange->opening_balance = 0;
            $supplieraccountchange->save();
            }
        else{
        switch ($currency) {
    case "USD":
        $totalcostconverted = $totalcost * 3.67;
        break;
    case "EUR":
        $totalcostconverted = $totalcost * 3.94;
        break;
    case "GBP":
        $totalcostconverted = $totalcost * 4.67;
        break;
    case "JPY":
        $totalcostconverted = $totalcost * 0.023;
        break;
    case "CAD":
        $totalcostconverted = $totalcost * 2.68;
        break;
    default:
        $totalcostconverted = $totalcost;
        }
        $supplieraccountchange->current_balance -= $totalcostconverted;
        $supplieraccountchange->save();
        }
        $supplieraccount = new SupplierAccountTransaction();
        $supplieraccount->transaction_type = "Credit";
        $supplieraccount->purchasing_order_id = $purchasedorder->id;
        $supplieraccount->supplier_account_id = $supplieraccountchange->id;
        $supplieraccount->created_by = auth()->user()->id;
        $supplieraccount->account_currency = $currency;
        $supplieraccount->transaction_amount = $totalcost;
        $supplieraccount->save();
            return redirect()->back()->with('success', 'Payment Status Updated');
       }
       public function allpaymentreqssfinpaycomp(Request $request)
       {
           $id = $request->input('orderId');
           $status = $request->input('status');
           $vehicles = DB::table('vehicles')
           ->where('purchasing_order_id', $id)
           ->where('status', 'Payment Requested')
           ->where('payment_status', 'Payment Release Approved')
           ->get();
           $vehicleCount = $vehicles->count();
           if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileNameToStore = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/swift_copies'), $fileNameToStore);            
            
            $latestBatch = DB::table('purchasing_order_swift_copies')
                ->where('purchasing_order_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            $batchNo = $latestBatch ? $latestBatch->batch_no + 1 : 1;
            
            $swiftcopy = new PurchasingOrderSwiftCopies();
            $swiftcopy->purchasing_order_id = $id;
            $swiftcopy->uploaded_by = auth()->user()->id;
            $swiftcopy->number_of_vehicles = $vehicleCount;
            $swiftcopy->batch_no = $batchNo;
            $swiftcopy->file_path = 'storage/swift_copies/' . $fileNameToStore;
            $swiftcopy->save();
        }        
           foreach ($vehicles as $vehicle) {
               $status = 'Payment Completed';
               $payment_status = 'Payment Completed';
               DB::table('vehicles')
                   ->where('id', $vehicle->id)
                   ->update(['status' => $status, 'payment_status' => $payment_status]);
                   $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                   $currentDateTime = Carbon::now($dubaiTimeZone);
                       $vehicleslog = new Vehicleslog();
                       $vehicleslog->time = $currentDateTime->toTimeString();
                       $vehicleslog->date = $currentDateTime->toDateString();
                       $vehicleslog->status = 'Payment Completed';
                       $vehicleslog->vehicles_id = $vehicle->id;
                       $vehicleslog->field = "Vehicle Status, Payment Status";
                       $vehicleslog->old_value = "Payment Release Approved";
                       $vehicleslog->new_value = "Payment Completed";
                       $vehicleslog->created_by = auth()->user()->id;
                       $vehicleslog->role = Auth::user()->selectedRole;
                       $vehicleslog->save();
                       $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                       $currentDateTime = Carbon::now($dubaiTimeZone);
                       $paymentlogs = new PaymentLog();
                       $paymentlogs->date = $currentDateTime->toDateString();
                       $paymentlogs->vehicle_id = $vehicle->id;
                       $paymentlogs->created_by = auth()->user()->id;
                       $paymentlogs->save();
                   }
                   return redirect()->back()->with('success', 'Payment Status Updated');
              }
              public function allpaymentintreqpocomp(Request $request)
              {
                  $id = $request->input('orderId');
                  $status = $request->input('status');
                  $vehicles = DB::table('vehicles')
                  ->where('purchasing_order_id', $id)
                  ->where('status', 'Payment Completed')
                  ->where('payment_status', 'Payment Completed')
                  ->get();
                  foreach ($vehicles as $vehicle) {
                      $status = 'Vendor Confirmed';
                      $payment_status = 'Vendor Confirmed';
                      DB::table('vehicles')
                          ->where('id', $vehicle->id)
                          ->update(['status' => $status, 'payment_status' => $payment_status]);
                          $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Vendor Confirmed';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Completed";
            $vehicleslog->new_value = "Vendor Confirmed";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
                          }
                          return redirect()->back()->with('success', 'Payment Status Updated');
       }
         public function allpaymentintreqpocompin(Request $request)
         {
             $id = $request->input('orderId');
             $status = $request->input('status');
             $vehicles = DB::table('vehicles')
             ->where('purchasing_order_id', $id)
             ->where('status', 'Vendor Confirmed')
             ->where('payment_status', 'Vendor Confirmed')
             ->get();
             foreach ($vehicles as $vehicle) {
                 $status = 'Incoming Stock';
                 $payment_status = 'Incoming Stock';
                 DB::table('vehicles')
                     ->where('id', $vehicle->id)
                     ->update(['status' => $status, 'payment_status' => $payment_status]);
                     $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                       $currentDateTime = Carbon::now($dubaiTimeZone);
                       $vehicleslog = new Vehicleslog();
                       $vehicleslog->time = $currentDateTime->toTimeString();
                       $vehicleslog->date = $currentDateTime->toDateString();
                       $vehicleslog->status = 'Incoming Stock';
                       $vehicleslog->vehicles_id = $vehicle->id;
                       $vehicleslog->field = "Vehicle Status, Payment Status";
                       $vehicleslog->old_value = "Vendor Confirmed";
                       $vehicleslog->new_value = "Incoming Stock";
                       $vehicleslog->created_by = auth()->user()->id;
                       $vehicleslog->role = Auth::user()->selectedRole;
                       $vehicleslog->save();
                  }
                     return redirect()->back()->with('success', 'Payment Status Updated');
                }
       public function approvedcancel($id)
                            {
            $vehicle = Vehicles::findOrFail($id);
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'Vehicle Cancel';
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->purchasing_order_id = $vehicle->purchasing_order_id;
            $purchasinglog->variant = $vehicle->varaints_id;
            $purchasinglog->estimation_date = $vehicle->estimation_date;
            $purchasinglog->territory = $vehicle->territory;
            $purchasinglog->int_colour = $vehicle->int_colour;
            $purchasinglog->ex_colour = $vehicle->ex_colour;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->save();
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = now()->toTimeString();
            $vehicleslog->date = now()->toDateString();
            $vehicleslog->status = 'Vehicle Cancel';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Status";
            $vehicleslog->old_value = $vehicle->status;
            $vehicleslog->new_value = 'Vehicle Cancel';
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $updateqty = PurchasingOrderItems::where('variant_id', $vehicle->varaints_id)
                ->where('purchasing_order_id', $vehicle->purchasing_order_id)
                ->first();
            if($updateqty)
            {
                $updateqty->qty = intval($updateqty->qty) - 1;
                $updateqty->save();
            }
            $updateprice = VehiclePurchasingCost::where('vehicles_id', $id)->first();
            if($updateprice)
            {
            $updatetotal = PurchasingOrder::find($vehicle->purchasing_order_id);
            $updatetotal->totalcost = $updatetotal->totalcost - $updateprice->unit_price;
            $updatetotal->save();
            }
                if($vehicle->model_id) {
                    $masterModel = MasterModel::find($vehicle->model_id);
                    $possibleModelIds = MasterModel::where('model', $masterModel->model)
                                            ->where('sfx', $masterModel->sfx)->pluck('id');
                    $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
                                            ->whereIn('master_model_id', $possibleModelIds)
                                            ->first();
                    $inventoryItem->purchase_order_id = NULL;
                    $inventoryItem->pfi_id = NULL;
                    $inventoryItem->letter_of_indent_item_id  = NULL;
                    $inventoryItem->save();

                    $purchaseOrderItem = PurchasingOrderItems::where('purchasing_order_id', $vehicle->purchasing_order_id)
                                                ->where('variant_id', $vehicle->varaints_id)->first();
                    if($purchaseOrderItem) {
                        $purchaseOrderItem->qty = $purchaseOrderItem->qty - 1;
                        $purchaseOrderItem->save();
                    }

                    $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                        ->where('master_model_id', $vehicle->model_id)
                        ->first();
                    $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity - 1;
                    $loiPurchaseOrder->save();
                }
            $vehicle->delete();
            return redirect()->back()->with('success', 'Vehicle cancellation request submitted successfully.');
        }
        public function updatebasicdetails(Request $request)
        {
            $purchasingOrder = PurchasingOrder::find($request->input('purchasing_order_id'));
            if (!$purchasingOrder) {
                return response()->json(['error' => 'Purchasing order not found'], 404);
            }
            $purchasingOrder->vendors_id = $request->input('vendors_id');
            $purchasingOrder->payment_term_id = $request->input('payment_term_id');
            $purchasingOrder->currency = $request->input('currency');
            $purchasingOrder->shippingmethod = $request->input('shippingmethod');
            $purchasingOrder->shippingcost = $request->input('shippingcost');
            $purchasingOrder->pol = $request->input('pol');
            $purchasingOrder->pod = $request->input('pod');
            $purchasingOrder->fd = $request->input('fd');
            $purchasingOrder->pl_number = $request->input('pl_number');
            $purchasingOrder->po_number = $request->input('po_number');
            $purchasingOrder->status = "Pending Approval";
            info($request->hasFile('uploadPL'));
            if ($request->hasFile('uploadPL')) {
                // Get file with extension
                $fileNameWithExt = $request->file('uploadPL')->getClientOriginalName();
        
                // Get just the filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        
                // Get just the extension
                $extension = $request->file('uploadPL')->getClientOriginalExtension();
        
                // Create a unique filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
        
                // Move the file to the public storage path
                $path = $request->file('uploadPL')->move(public_path('storage/PL_Documents'), $fileNameToStore);
        
                // Update the file path in the purchasing order
                $purchasingOrder->pl_file_path = 'storage/PL_Documents/' . $fileNameToStore;
            }
            $purchasingOrder->save();
            return response()->json(['message' => 'Purchase order details updated successfully'], 200);
        }
        public function pendingvins($status)
{
$userId = auth()->user()->id;
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
if ($hasPermission){
$data = PurchasingOrder::with('purchasing_order_items')
    ->where('created_by', '!=', '16')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('vehicles')
              ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
              ->whereNull('deleted_at')
              ->whereNull('vin'); // Check for at least one VIN being null
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    ->where('created_by', '!=', '16')
    // ->where('created_by', $userId)->orWhere('created_by', 16)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('vehicles')
              ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
              ->whereNull('deleted_at')
              ->whereNull('vin'); // Check for at least one VIN being null
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
return view('warehouse.index', compact('data'));
}
public function rerequestpayment(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $vehicles = DB::table('vehicles')
    ->where('purchasing_order_id', $id)
    ->where('status', 'Payment Requested')
    ->orwhere('status', 'Payment Rejected')
    ->where('payment_status', 'Payment Release Rejected')
    ->get();
    info($vehicles);
    foreach ($vehicles as $vehicle) {
        $status = 'Payment Requested';
        $payment_status = 'Payment Initiated Request';
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['status' => $status, 'payment_status' => $payment_status]);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Re Payment Initiated';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "Vehicle Status, Payment Status";
                $vehicleslog->old_value = "Payment Re Initiate Request";
                $vehicleslog->new_value = "Payment Release Rejected";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            }
            return redirect()->back()->with('success', 'Payment Status Updated');
       }
       public function repaymentintiation($id)
       {
           $vehicle = Vehicles::find($id);
           if ($vehicle) {
               $vehicle->status = 'Payment Requested';
               $vehicle->payment_status = 'Payment Initiated Request';
               $vehicle->save();
               $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
               $currentDateTime = Carbon::now($dubaiTimeZone);
                   $vehicleslog = new Vehicleslog();
                   $vehicleslog->time = $currentDateTime->toTimeString();
                   $vehicleslog->date = $currentDateTime->toDateString();
                   $vehicleslog->status = 'Payment Re Initiated Request';
                   $vehicleslog->vehicles_id = $id;
                   $vehicleslog->field = "Vehicle Status, Payment Status";
                   $vehicleslog->old_value = "Payment Released Rejected";
                   $vehicleslog->new_value = "Payment Re Initiated Request";
                   $vehicleslog->created_by = auth()->user()->id;
                   $vehicleslog->role = Auth::user()->selectedRole;
                   $vehicleslog->save();
               return redirect()->back()->with('success', 'Payment Initiated Request confirmed. Vehicle status updated.');
           }
           return redirect()->back()->with('error', 'Vehicle not found.');
       }
       public function cancelpo(Request $request, $id)
    {
        $purchasingOrder = PurchasingOrder::find($id);
        if ($purchasingOrder) {
            $purchasingOrder->status = 'Cancel Request';
            $purchasingOrder->remarks = $request->input('remarks');
            $purchasingOrder->save();
            $purchasinglog = new Purchasinglog();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'PO Cancelled Request';
            $purchasinglog->purchasing_order_id = $id;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->save();
            // Respond with a JSON object indicating success and redirect URL
            return response()->json([
                'success' => true,
                'redirectUrl' => route('purchasing-order.index')
            ]);
        }
        // Respond with an error if the purchasing order was not found
        return response()->json([
            'success' => false,
            'message' => 'Purchasing order not found.'
        ], 404);
    }
    public function purchasingupdateStatuscancel(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Cancel Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $id = $request->input('orderId');
        $status = $request->input('status');
        $purchasingOrder = PurchasingOrder::find($id);
        if($status == "Rejected")
        {
            $purchasingOrder->status = "Approved";
            $purchasingOrder->save();
        }
        else
        {
            $purchasingOrder->status = "Cancelled";
            $purchasingOrder->save();
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'PO Cancelled';
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->purchasing_order_id = $id;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->save();
            $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
            foreach ($vehicles as $vehicle) {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = now()->toTimeString();
                $vehicleslog->date = now()->toDateString();
                $vehicleslog->status = 'Vehicle Cancel';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Status";
                $vehicleslog->old_value = $vehicle->status;
                $vehicleslog->new_value = 'Vehicle Cancel';
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
                $vehicle->delete();   
            }
        }
        return response()->json([
            'success' => true,
            'redirectUrl' => route('purchasing-order.index')
        ]);
    }
    public function paymentintconfirmrej($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Approved';
        $vehicle->payment_status = Null;
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Request Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Request for Initiate Payment";
            $vehicleslog->new_value = "Payment Initiated Request Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiated Request Rejected confirmed');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function getSupplierAndAmount($orderId) {
    $order = PurchasingOrder::find($orderId);
    if ($order) {
        $vendors_id = $order->vendors_id;
        $supplier = SupplierAccount::where('suppliers_id', $vendors_id)->first();
        $requestedcost = DB::table('vehicles')
        ->join('vehicle_purchasing_cost', 'vehicles.id', '=', 'vehicle_purchasing_cost.vehicles_id')
        ->where('vehicles.purchasing_order_id', $orderId)
        ->where('vehicles.payment_status', 'Payment Initiated Request')
        ->sum('vehicle_purchasing_cost.unit_price');
        $current_amount = $supplier->current_balance;
        $totalamount = $order->totalcost;
        $requestedcost = $requestedcost;
        return response()->json(['supplier_id' => $vendors_id, 'current_amount' => $current_amount, 'totalamount' => $totalamount, 'requestedcost' => $requestedcost]);
    }
    return response()->json(['error' => 'Order not found'], 404);
}
public function vehiclesdatagetting($id)
{
    $vehicles = Vehicles::where('purchasing_order_id', $id)->whereNull('deleted_at')->get();
    $vehicleData = [];
    foreach ($vehicles as $vehicle) {
        $price = VehiclePurchasingCost::where('vehicles_id', $vehicle->id)->value('unit_price');
        $vehicleData[] = [
            'vehicle_id' => $vehicle->id,
            'vin' => $vehicle->vin,
            'price' => intval($price),
        ];
    }
    return response()->json($vehicleData);
}
public function updatePrices(Request $request)
{
    $prices = $request->input('prices');
    $totalPrice = intval($request->input('total_price'));
    $purchasingOrderId = $request->input('purchasing_order_id');
    $userId = auth()->id(); // Assuming you have user authentication and need the ID of the user making the request

    // Fetch the purchasing order and its currency
    $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
    $orderCurrency = $purchasingOrder->currency;
    // Fetch the supplier account linked to this purchasing order
    $supplierAccount = SupplierAccount::where('suppliers_id', $purchasingOrder->vendors_id)->first();
    $accountCurrency = $supplierAccount->currency;
    // Currency conversion rates
    $conversionRates = [
        'USD' => 3.67,
        'EUR' => 3.94,
        'GBP' => 4.66,
        'JPY' => 0.023,
        'CAD' => 2.69
    ];
    $totalDifference = 0;
    foreach ($prices as $priceData) {
        $vehicleId = $priceData['vehicle_id'];
        $newPrice = $priceData['new_price'];
        // Fetch the old price
        $vehicleCost = VehiclePurchasingCost::where('vehicles_id', $vehicleId)->first();
        $oldPrice = $vehicleCost->unit_price;
        // Calculate the price difference
        $priceDifference = $oldPrice - $newPrice;
        if ($priceDifference != 0) {
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update Vehicles Price';
            $vehicleslog->vehicles_id = $vehicleId;
            $vehicleslog->field = "Price";
            $vehicleslog->old_value = $vehicleCost->unit_price;
            $vehicleslog->new_value = $newPrice;
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $statuses = [
                'Payment Release Approved', 
                'Payment Completed', 
                'Vendor Confirmed', 
                'Incoming Stock'
            ];
            $vehiclesalreadypaid = Vehicles::where('id', $vehicleId)
                               ->whereIn('payment_status', $statuses)
                               ->first();
            if($vehiclesalreadypaid)
            {
                if ($vehicleCost->unit_price > $newPrice) {
                    $changeType = 'discount';
                } else {
                    $changeType = 'Surcharge';
                }
                $priceChange = abs($vehicleCost->unit_price - $newPrice);
                $priceupdates = New PurchasedOrderPriceChanges ();
                $priceupdates->purchasing_order_id = $purchasingOrderId;
                $priceupdates->vehicles_id = $vehicleId;
                $priceupdates->original_price = $vehicleCost->unit_price;
                $priceupdates->new_price = $newPrice;
                $priceupdates->price_change = $priceChange;
                $priceupdates->change_type = $changeType;
                $priceupdates->save();
            }
            $vehicleCost->update(['unit_price' => $newPrice]);
            $updatepriceinpaid = PurchasedOrderPaidAmounts::where('purchasing_order_id', $purchasingOrderId)->where('status', 'Request For Payment')->orderBy('created_at', 'desc')->first();
            $updatePerformed = false;
            if ($updatepriceinpaid) {
                // Check if priceDifference is positive or negative 
                if ($priceDifference > 0) {
                    $updatepriceinpaid->amount -= $priceDifference; // Add priceDifference
                } else {
                    $updatepriceinpaid->amount += abs($priceDifference); // Subtract priceDifference
                }
                $updatepriceinpaid->save();
                $updatePerformed = true;
            }
            $vehicles = Vehicles::where('id', $vehicleId)->first();
            // Skip account updates if payment status is blank or 'Payment Initiated Request'
            if ($vehicles && ($vehicles->payment_status == '' || $vehicles->payment_status == 'Payment Initiated Request')) {
                continue;
            }
            if (!$updatePerformed) {
            $updatepriceinpaidint = PurchasedOrderPaidAmounts::where('purchasing_order_id', $purchasingOrderId)->where('status', 'Initiated Payment')->orderBy('created_at', 'desc')->first();
            if ($updatepriceinpaidint) {
                // Check if priceDifference is positive or negative 
                if ($priceDifference > 0) {
                    $updatepriceinpaidint->amount -= $priceDifference; // Add priceDifference
                } else {
                    $updatepriceinpaidint->amount += abs($priceDifference); // Subtract priceDifference
                }
                $updatepriceinpaidint->save();
            }
         }
        }
        // Convert the price difference to supplier account currency if needed
        if ($orderCurrency !== $accountCurrency) {
            $priceDifferenceInAccountCurrency = $this->convertCurrency($priceDifference, $orderCurrency, $accountCurrency, $conversionRates);
        } else {
            $priceDifferenceInAccountCurrency = $priceDifference;
        }

        // Accumulate the total difference
        $totalDifference += $priceDifferenceInAccountCurrency;
    }
    // Update supplier account current balance if the total difference is not zero and not skipped
    if ($totalDifference != 0) {
        $supplierAccount->current_balance += $totalDifference;
        $supplierAccount->save();

        // Record the transaction
        SupplierAccountTransaction::create([
            'transaction_type' => $totalDifference > 0 ? 'Debit' : 'Credit',
            'purchasing_order_id' => $purchasingOrderId,
            'supplier_account_id' => $supplierAccount->id,
            'created_by' => $userId,
            'account_currency' => $accountCurrency,
            'transaction_amount' => abs($totalDifference),
        ]);
    }
    // Update the total price in the purchasing order
    $purchasingOrder->update(['totalcost' => $totalPrice]);
    return response()->json(['message' => 'Prices updated successfully']);
}
private function convertCurrency($amount, $fromCurrency, $toCurrency, $conversionRates)
{
    if ($fromCurrency == 'AED') {
        // Convert from AED to the target currency
        return $amount / $conversionRates[$toCurrency];
    } elseif ($toCurrency == 'AED') {
        // Convert from the source currency to AED
        return $amount * $conversionRates[$fromCurrency];
    } else {
        // Convert from source currency to AED, then from AED to target currency
        $amountInAed = $amount * $conversionRates[$fromCurrency];
        return $amountInAed / $conversionRates[$toCurrency];
    }
}
public function storeMessages(Request $request)
    {
        $message = PurchasedOrderMessages::create([
            'purchasing_order_id' => $request->purchase_order_id,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        return response()->json($message->load('user'));
    }
    public function storeReply(Request $request)
    {
        $reply = PurchasedOrderReplies::create([
            'purchased_order_messages_id' => $request->message_id,
            'user_id' => auth()->id(),
            'reply' => $request->reply
        ]);

        return response()->json($reply->load('user'));
    }
    public function indexmessages($purchaseOrderId)
    {
        $messages = PurchasedOrderMessages::where('purchasing_order_id', $purchaseOrderId)
                            ->with('user', 'replies.user')
                            ->get();

        return response()->json($messages);
    }
    public function vehiclesdatagettingvariants($id)
{
    $vehicles = Vehicles::with('variant')->where('purchasing_order_id', $id)->whereNull('deleted_at')->get();
    $vehicleData = [];
    foreach ($vehicles as $vehicle) {
        $vehicleData[] = [
            'vehicle_id' => $vehicle->id,
            'vin' => $vehicle->vin,
            'variant_name' => $vehicle->variant->name ?? 'N/A',
        ];
    }
    return response()->json($vehicleData);
}
public function updateVariants(Request $request)
{
    $variants = $request->input('variants');
    $purchasingOrderId = $request->input('purchasing_order_id');
    foreach ($variants as $variant) {
        $vehicle = Vehicles::where('id', $variant['vehicle_id'])
            ->where('purchasing_order_id', $purchasingOrderId)
            ->first();
        if ($vehicle) {
            $vehicle->varaints_id = $variant['variant_id'];
            $vehicle->save();
        }
    }
    PurchasingOrderItems::where('purchasing_order_id', $purchasingOrderId)->delete();
    $vehiclesGroupedByVariant = Vehicles::where('purchasing_order_id', $purchasingOrderId)
        ->selectRaw('varaints_id, COUNT(*) as qty')
        ->groupBy('varaints_id')
        ->get();
    foreach ($vehiclesGroupedByVariant as $group) {
        $purchasedorderitems = New PurchasingOrderItems();
        $purchasedorderitems->purchasing_order_id = $purchasingOrderId;
        $purchasedorderitems->variant_id = $variant['variant_id'];
        $purchasedorderitems->qty = $group->qty;
        $purchasedorderitems->save();
    }
    return response()->json(['success' => true]);
}
}
