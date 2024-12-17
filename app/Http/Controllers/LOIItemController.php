<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\LetterOfIndent;
use App\Models\PFI;
use App\Models\PfiItem;
use App\Models\Clients;
use App\Models\Country;
use App\Models\User;
use App\Models\MasterModelLines;
use App\Models\LetterOfIndentItem;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Builder;
use App\Models\LoiSoNumber;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;

class LOIItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        (new UserActivityController)->createActivity('Open LOI Items List Page');

        $customers = Clients::where('is_demand_planning_customer', true)->select('id','name')->groupBy('name')->get();
        $countries = Country::select('id','name')->get();
        $modelLines = MasterModelLines::select('id','model_line')->get();
        $users = User::select('id','name')->get();

        $data = LetterOfIndentItem::select("*", DB::raw('quantity - utilized_quantity as remaining_quantity'))
                ->orderBy('updated_at','DESC')->with([
                'LOI' => function ($query) {
                    $query->select('id','uuid','client_id','date','category','loi_approval_date',
                    'is_expired','dealers','status','sales_person_id','review','comments','country_id');
                },
                'LOI.client'  => function ($query) {
                    $query->select('id','name','customertype','country_id');
                },
                'LOI.country'  => function ($query) {
                    $query->select('id','name');
                },
                'masterModel' => function($query){
                    $query->select('id','model', 'sfx','master_model_line_id','steering');
                },
                'masterModel.modelLine' => function($query){
                    $query->select('id','model_line');
                },
                'LOI.salesPerson' => function($query){
                    $query->select('id', 'name');
                },
                'LOI.soNumbers' => function($query){
                    $query->select('id','so_number');
                },
                'pfiItems.pfi' => function($query){
                    $query->select('id','pfi_reference_number');
                }]);
                
                if(!empty($request->pfi_number)) {
                    $data->whereHas('pfiItems.pfi',function($query) use($request) {
                            $query->where('pfi_reference_number', 'like', "%{$request->pfi_number}%");
                        });
                }
                if(!empty($request->uuid)) {
                    $data->whereHas('LOI',function($query) use($request) {
                            $query->where('uuid', 'like', "%{$request->uuid}%");
                        });
                }
                if(!empty($request->loi_date)) {
                    $data->whereHas('LOI',function($query) use($request) {
                            $query->whereDate('date', $request->loi_date);
                        });
                }
                if(!empty($request->loi_approval_date)) {
                    $data->whereHas('LOI',function($query) use($request) {
                            $query->whereDate('loi_approval_date', $request->loi_approval_date);
                        });
                }
                if(!empty($request->dealer)) {
                    $data->whereHas('LOI',function($query) use($request) {
                        $query->where('dealers', $request->dealer);
                    });
                }
                if(!empty($request->dealer)) {
                    $data->whereHas('LOI',function($query) use($request) {
                        $query->where('dealers', $request->dealer);
                    });
                }
                if(!empty($request->client_id)) {
                    $data->whereHas('LOI',function($query) use($request) {
                            $query->where('client_id',$request->client_id);
                        });
                }
                if(!empty($request->customer_type)) {
                    $data->whereHas('LOI.client',function($query) use($request) {
                            $query->where('customertype', $request->customer_type);
                        });
                }
                if(!empty($request->category)) {
                    $data->whereHas('LOI',function($query) use($request) {
                            $query->where('category', $request->category);
                        });
                }
                if(!empty($request->country_id)) {
                    $data->whereHas('LOI.country',function($query) use($request) {
                            $query->where('country_id', $request->country_id);
                        });
                }
                if(!empty($request->loi_item_code)) {
                    $data->where('code', 'like', "%{$request->loi_item_code}%");
                }
                if(!empty($request->model)) {
                    $data->whereHas('masterModel',function($query) use($request) {
                        $query->where('model',  'like', "%{$request->model}%");
                    });
                }
                if(!empty($request->sfx)) {
                    $data->whereHas('masterModel',function($query) use($request) {
                        $query->where('sfx',  'like', "%{$request->sfx}%");
                    });
                }
                if(!empty($request->steering)) {
                    $data->whereHas('masterModel',function($query) use($request) {
                        $query->where('steering', $request->steering);
                    });
                }
                if(!empty($request->model_line)) {
                    $data->whereHas('masterModel.modelLine',function($query) use($request) {
                        $query->where('id', $request->model_line);
                    });
                }
                if(!empty($request->quantity)) {
                    $data->where('quantity', 'like', "%{$request->quantity}%");
                }
                if(!empty($request->utilized_quantity)) {
                    $data->where('utilized_quantity', 'like', "%{$request->utilized_quantity}%");
                }
                if(!empty($request->remaining_quantity)) {
                    $data->having('remaining_quantity', 'like', "%{$request->remaining_quantity}%");
                }
                if(!empty($request->sales_person)) {
                    $data->whereHas('LOI.salesPerson',function($query) use($request) {
                        $query->where('id', $request->sales_person);
                    });
                }
                if(!empty($request->is_expired)) {
                    $data->whereHas('LOI',function($query) use($request) {
                        $query->where('is_expired', $request->is_expired);
                    });
                }
                if(!empty($request->status)) {
                    $data->whereHas('LOI',function($query) use($request) {
                        $query->whereIn('status', $request->status);
                    });
                }
                if(!empty($request->so_number)) {
                    $data->whereHas('LOI.soNumbers',function($query) use($request) {
                        $query->where('so_number', 'like', "%{$request->so_number}%");
                    });
                }
                if(!empty($request->review)) {
                    $data->whereHas('LOI',function($query) use($request) {
                        $query->where('review', 'like', "%{$request->review}%");
                    });
                }
                if(!empty($request->comments)) {
                    $data->whereHas('LOI',function($query) use($request) {
                        $query->where('comments', 'like', "%{$request->comments}%");
                    });
                }
                if(!empty($request->loi_from_date && $request->loi_to_date)) {
            
                    $data->whereHas('LOI',function($query) use($request) {
                        $query->whereBetween('date',  [$request->loi_from_date, $request->loi_to_date]);
                    });
                }


                if($request->export == 'EXCEL') {
                    (new UserActivityController)->createActivity('Downloaded LOI Item List');

                    $data = $data->get();
                 
                  return (new FastExcel($data))->download('LOI-ITEMS.csv', function ($data) {
                    $soNumbers = LoiSoNumber::where('letter_of_indent_id', $data->LOI->id)
                                     ->pluck('so_number')->toArray();

                        $soNumbers = implode(",", $soNumbers);
                        if($data->LOI->is_expired == true) {
                            $is_expired = 'Expired';
                        }else{
                            $is_expired = 'Not Expired';
                        }  
                        // pfi number 
                        $loi_item_id = $data->id;
                        $pfiNumbers = [];
                        $pfiIds = PFI::with('pfiItems')
                        ->whereHas('pfiItems', function($q)use($loi_item_id){
                            $q->where('loi_item_id', $loi_item_id);
                        })
                        ->get();
                        foreach($pfiIds as $pfi) {
                            $pfiItem = PfiItem::where('loi_item_id', $loi_item_id)
                                    ->where('pfi_id', $pfi->id)
                                    ->first();
                            $pfiNumbers[] = $pfi->pfi_reference_number. ' - ('. $pfiItem->pfi_quantity .')';
                        }

                        $pfiNumbers = implode("<br>", $pfiNumbers); 
                        
                        return [
                            'LOI Number' => $data->LOI->uuid,
                            'LOI Date' => $data->LOI->date,
                            'LOI Approval Date' => $data->LOI->loi_approval_date,
                            'Dealer' => $data->LOI->dealers,
                            'Category' => $data->LOI->category,
                            'Customer Name' => $data->LOI->client->name ?? '',
                            'Customer Type' => $data->LOI->client->customertype ?? '',
                            'Country' => $data->LOI->country->name ?? '',
                            'Item Code' => $data->code,
                            'Model' => $data->masterModel->model,
                            'SFX' => $data->masterModel->sfx,
                            'Steering' => $data->masterModel->steering,
                            'Model Line' => $data->masterModel->modelLine->model_line,
                            'PFI Number - (QTY)' => $pfiNumbers,
                            'Quantity' => $data->quantity,
                            'Utilized Quantity' => $data->utilized_quantity,
                            'Remaining Quantity' => $data->remaining_quantity,
                            'Sales Person' => $data->LOI->salesPerson->name ?? '',
                            'So Numbers' => $soNumbers,
                            'Status' => $data->LOI->status,
                            'Is Expired' => $is_expired,
                            'Approval Remarks' => $data->LOI->review,
                            'LOI Comments' => $data->LOI->comments
                        ];
                    });
                }

        if (request()->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('loi_date', function($query) {
                    return Carbon::parse($query->LOI->date)->format('d M Y') ?? '';
                })
                ->addColumn('loi_approval_date', function($query) {
                    if($query->LOI->loi_approval_date) {
                        return Carbon::parse($query->LOI->loi_approval_date)->format('d M Y');
                    }
                })
                ->addColumn('so_number', function($query) {
                    $soNumbers = LoiSoNumber::where('letter_of_indent_id', $query->LOI->id)
                            ->pluck('so_number')->toArray();
                   return implode(",", $soNumbers);
                })
                ->addColumn('pfi_number', function($query) {
                    $loi_item_id = $query->id;
                    $pfiNumbers = [];
                    $pfiIds = PFI::with('pfiItems')
                    ->whereHas('pfiItems', function($q)use($loi_item_id){
                        $q->where('loi_item_id', $loi_item_id);
                    })
                    ->get();
                    foreach($pfiIds as $pfi) {
                        $pfiItem= PfiItem::where('loi_item_id', $loi_item_id)
                                ->where('pfi_id', $pfi->id)->first();
                                $pfiNumbers[] = $pfi->pfi_reference_number. ' - ('. $pfiItem->pfi_quantity .')';
                    }
                   return implode("<br>", $pfiNumbers);
                })
                ->addColumn('sales_person_id', function($query) {                    
                    if($query->LOI->sales_person_id){
                        return $query->LOI->salesPerson->name ?? '';
                    }
                 })
                ->addColumn('is_expired', function($query) {
                    if($query->LOI->is_expired == true) {
                        $msg = 'Expired';
                        return  '<button class="btn btn-sm btn-secondary">'.$msg.'</button>';
                    }else{
                        $msg = 'Not Expired';
                        return '<button class="btn btn-sm btn-info">'.$msg.'</button>';
                    }                                           
                 })
                 ->addColumn('status', function($query) {
                    if($query->LOI->status == LetterOfIndent::LOI_STATUS_NEW) {
                        return  '<button class="btn btn-sm btn-primary">'.LetterOfIndent::LOI_STATUS_NEW.'</button>';
                    }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL) {
                         return '<button class="btn btn-sm btn-warning">'.LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL.'</button>';
                     }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED) {
                         return  '<button class="btn btn-sm btn-success">'.LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED.'</button>';
                     }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED) {
                         return  '<button class="btn btn-sm btn-danger">'.LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED.'</button>';
                     }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_WAITING_FOR_TTC_APPROVAL) {
                        return  '<button class="btn btn-sm btn-warning">'.LetterOfIndent::LOI_STATUS_WAITING_FOR_TTC_APPROVAL.'</button>';
                    }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_TTC_APPROVED) {
                        return  '<button class="btn btn-sm btn-primary">'.LetterOfIndent::LOI_STATUS_TTC_APPROVED.'</button>';
                    }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_TTC_REJECTED) {
                        return  '<button class="btn btn-sm btn-danger">'.LetterOfIndent::LOI_STATUS_TTC_REJECTED.'</button>';
                    }else{
                        return $query->LOI->status;
                     }                                       
                 })
                ->rawColumns(['loi_date','is_expired','sales_person_id','so_number','status',
                             'loi_approval_date','pfi_number'])
                ->toJson();
            }
        
            return view('letter_of_indents.letter_of_indent_items.index', compact('customers','countries','modelLines',
                    'users'));

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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
