<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LetterOfIndent;
use App\Models\MasterModel;
use App\Models\LetterOfIndentItem;
use App\Models\PFI;
use App\Models\PfiItem;
use App\Models\PfiItemPurchaseOrder;
use App\Models\PurchasingOrder;
use App\Models\SupplierAccountTransaction;
use App\Models\Supplier;
use App\Models\Clients;
use App\Models\Country;
use App\Models\ClientCountry;
use App\Models\Brand;
use App\Models\MasterModelLines;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfReader\Page;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\FastExcel\FastExcel;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;

class PFIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open PFI List Section');

        $data = PFI::orderBy('id','DESC')->with([
            'customer' => function ($query) {
                $query->select('id','name');
            },
            'supplier'  => function ($query) {
                $query->select('id','supplier');
            },
            'country'  => function ($query) {
                $query->select('id','name');
            },
            'createdBy' => function ($query) {
                $query->select('id', 'name');
            },
            'updatedBy' => function($query){
                    $query->select('id', 'name');
                },
            ]);

            if (request()->ajax()) {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('created_by', function($query) {
                        return $query->CreatedBy->name ?? '';
                    })
                    ->editColumn('amount', function($query) {
                        return number_format($query->amount);
                    })
                    ->editColumn('released_amount', function($query) {
                        return number_format($query->released_amount);
                    })
                    ->editColumn('released_date', function($query) {
                        if($query->released_date) {
                            return Carbon::parse($query->released_date)->format('d M Y') ?? '';
                        }
                    })
                    ->editColumn('created_at', function($query) {
                        return Carbon::parse($query->created_at)->format('d M Y');
                    })
                    ->editColumn('updated_by', function($query) {                  
                        if($query->updated_by){
                            return $query->updatedBy->name ?? '';
                        }
                    })
                    ->editColumn('updated_at', function($query) {
                        if($query->updated_at) {
                            return Carbon::parse($query->updated_at)->format('d M Y');
                        }
                        return "";
                    })
                    ->editColumn('pfi_date', function($query) {
                        if($query->pfi_date) {
                            return Carbon::parse($query->pfi_date)->format('d M Y');
                        }
                        return " ";
                    })
                    ->addColumn('po_number', function($query) {
                        $pfiId = $query->id;
                        $PONumbers = PurchasingOrder::whereHas('PfiItemPurchaseOrders', function($params) use($pfiId) {
                            $params->where('pfi_id', $pfiId)->groupBy('po_number');
                        })
                        ->pluck('po_number')->toArray();
                    
                        return implode(", ", $PONumbers);  
                    })
                    ->addColumn('action', function(PFI $pfi) {
                        $parentPfiItems =  PfiItem::where('is_parent', true)->where('pfi_id', $pfi->id)->get();
                        foreach($parentPfiItems as $item) {
                            $LOICodes = LetterOfIndentItem::whereHas('pfiItems', function($query) use($pfi,$item) {
                                $query->where('pfi_id', $pfi->id)->where('parent_pfi_item_id', $item->id);
                            })
                            ->pluck('code')->toArray();

                            $item->loi_item_code = implode(",", $LOICodes);  
                        }
                        $newPFIFileName = "";
                        if($pfi->new_pfi_document_without_sign) {
                            $filename = $pfi->new_pfi_document_without_sign;
                            $newPFIFileName =  strstr($filename, '_', true) . ".pdf";
                        }
                        $oldPFIFileName =  strstr($pfi->pfi_document_without_sign, '_', true) . ".pdf";
                        $showCreatePOBtn = 1;
                        // if pfi is toyota 
                        $isExistPO = PfiItemPurchaseOrder::select('pfi_id')->where('pfi_id', $pfi->id)->first();
                        if($isExistPO) {
                            if($pfi->is_toyota_pfi == true) {
                                // only one po allowed =>  not show btn
                                $showCreatePOBtn = 0;
                            }else{
                                $PoUtilizedQty = PfiItemPurchaseOrder::select('pfi_id')->where('pfi_id', $pfi->id)
                                                                ->sum('quantity');
                                if($PoUtilizedQty) {
                                    $pfiQty =  PfiItem::select('is_parent','pfi_id','pfi_quantity')
                                                ->where('is_parent', true)
                                                ->where('pfi_id', $pfi->id)
                                                ->sum('pfi_quantity');
                                    if($pfiQty <= $PoUtilizedQty) {
                                        $showCreatePOBtn = 0;
                                    }
                                }    
                            }
                        }
                        
                        return view('pfi.action',compact('pfi','parentPfiItems','oldPFIFileName','newPFIFileName','showCreatePOBtn'
                      ,'isExistPO'));
                    })
                    ->rawColumns(['action','po_number'])
                    ->toJson();
                }

            return view('pfi.index');
    }
    public function PFIItemList(Request $request) {
        (new UserActivityController)->createActivity('Open PFI Item List Section');
        // pass the data to show in filter options
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();
        $masterModels = MasterModel::with('modelLine')->select('id','master_model_line_id','model','sfx')
                                      ->groupBy('model')->get();
         $customers = Clients::where('is_demand_planning_customer', true)->select('id','name')->groupBy('name')->get();
         $countries = Country::select('id','name')->get();
         $brands = Brand::select('id','brand_name')->get();
         $modelLines = MasterModelLines::select('id','model_line')->get();
            /// end ///

        $data = PfiItem::select("*", DB::raw('pfi_quantity * unit_price as total_price'))
            ->where('is_parent', true)
            ->orderBy('updated_at','DESC')->with([
                'pfi' => function ($query) {
                    $query->select('id','supplier_id','country_id','client_id','pfi_reference_number','currency','amount','comment','pfi_date');
            },
            'masterModel'  => function ($query) {
                $query->select('id','model','sfx','steering','master_model_line_id');
            },
            'masterModel.modelLine'  => function ($query) {
                $query->select('id','model_line','brand_id');
            },
            'masterModel.modelLine.brand'  => function ($query) {
                $query->select('id','brand_name');
            },
            'pfi.customer'  => function ($query) {
                $query->select('id','name');
            },
            'pfi.supplier'  => function ($query) {
                $query->select('id','supplier');
            },
            'pfi.country'  => function ($query) {
                $query->select('id','name');
            }]);

           
            if(!empty($request->code)) {
                $data->whereHas('ChildPfiItems.letterOfIndentItem',function($query) use($request) {
                        $query->where('code', 'like', "%{$request->code}%");
                    });
            }

            if(!empty($request->pfi_date)) {
                $data->whereHas('pfi',function($query) use($request) {
                        $query->whereDate('pfi_date', $request->pfi_date);
                    });
            }
            if(!empty($request->released_date)) {
                $data->whereHas('PfiItemPurchaseOrders.purchaseOrder.SupplierAccountTransactions',function($query) use($request) {
                        $query->whereDate('payment_released_date', $request->released_date);
                    });
            }
            if(!empty($request->pfi_number)) {
                $data->whereHas('pfi',function($query) use($request) {
                        $query->where('pfi_reference_number', 'like', "%{$request->pfi_number}%");
                    });
            }
            if(!empty($request->po_number)) {
                $data->whereHas('PfiItemPurchaseOrders.purchaseOrder',function($query) use($request) {
                        $query->where('po_number', 'like', "%{$request->po_number}%");
                    });
            }
            if(!empty($request->payment_status)) {
                $data->whereHas('PfiItemPurchaseOrders.purchaseOrder',function($query) use($request) {
                        $query->where('payment_status', $request->payment_status);
                    });
            }
            if(!empty($request->payment_initiated_status)) {
                $data->whereHas('PfiItemPurchaseOrders.purchaseOrder',function($query) use($request) {
                        $query->where('payment_initiated_status', $request->payment_initiated_status);
                    });
            }
            if(!empty($request->currency)) {
                $data->whereHas('pfi',function($query) use($request) {
                        $query->where('currency', $request->currency);
                    });
            }
            if(!empty($request->steering)) {
                $data->whereHas('masterModel',function($query) use($request) {
                        $query->where('steering', $request->steering);
                    });
            }
            if(!empty($request->client_id)) {
                $data->whereHas('pfi',function($query) use($request) {
                        $query->where('client_id', $request->client_id);
                    });
            }
            if(!empty($request->country_id)) {
                $data->whereHas('pfi',function($query) use($request) {
                        $query->where('country_id', $request->country_id);
                    });
            }
            if(!empty($request->supplier_id)) {
                $data->whereHas('pfi',function($query) use($request) {
                        $query->where('supplier_id', $request->supplier_id);
                    });
            }
            if(!empty($request->brand)) {
                $data->whereHas('masterModel.modelLine.brand',function($query) use($request) {
                        $query->where('id', $request->brand);
                    });
            }
            if(!empty($request->model_line)) {
                $data->whereHas('masterModel.modelLine',function($query) use($request) {
                        $query->where('id', $request->model_line);
                    });
            }
            if(!empty($request->model)) {
                $data->whereHas('masterModel',function($query) use($request) {
                        $query->where('model',  'like', "%{$request->model}%");
                    });
            }
            if(!empty($request->sfx)) {
                $data->whereHas('masterModel',function($query) use($request) {
                        $query->where('sfx', 'like', "%{$request->sfx}%");
                    });
            }
            if(!empty($request->pfi_quantity)) {
                $data->where('pfi_quantity', 'like', "%{$request->pfi_quantity}%");
    
            }
            if(!empty($request->unit_price)) {
                $data->where('unit_price',  'like', "%{$request->unit_price}%");
    
            }
            if(!empty($request->pfi_amount)) {
                $data->whereHas('pfi',function($query) use($request) {
                    $query->where('amount',  'like', "%{$request->pfi_amount}%");
                });
            }
            if(!empty($request->comment)) {
                $data->whereHas('pfi',function($query) use($request) {
                    $query->where('comment', 'like', "%{$request->comment}%");
                });
            }
            if(!empty($request->total_price)) {
                $data->having("total_price", 'like', "%{$request->total_price}%");
            }
            if(!empty($request->pfi_item_code)) {
                $data->where("code", 'like', "%{$request->pfi_item_code}%");
            }
   
            if($request->tab == 'TOYOTA'){
                $data = $data->whereHas('masterModel.modelLine.brand',function($query) use($request) {
                    $query->where('brand_name', 'like', "TOYOTA");
                });

            }else if($request->tab == 'OTHER-BRAND'){
                $data = $data->whereHas('masterModel.modelLine.brand',function($query) use($request) {
                    $query->whereNot('brand_name', "TOYOTA");
                });
            }
            if($request->export == 'EXCEL') {
                (new UserActivityController)->createActivity('Downloaded PFI Item List');
                $data = $data->get();

              return (new FastExcel($data))->download('PFI-ITEMS.csv', function ($data) {
                        $pfiId = $data->pfi->id;
                        $parentPfiItemId = $data->id;
                        $LOICodes = LetterOfIndentItem::whereHas('pfiItems', function($params) use($pfiId,$parentPfiItemId) {
                            $params->where('pfi_id', $pfiId)->where('parent_pfi_item_id', $parentPfiItemId);
                        })
                        ->pluck('code')->toArray();
                    
                        $loiItemCode = implode(", ", $LOICodes);  
                        $PONumbers = PurchasingOrder::whereHas('PfiItemPurchaseOrders', function($params) use($pfiId,$parentPfiItemId) {
                            $params->where('pfi_id', $pfiId)->where('pfi_item_id', $parentPfiItemId);
                        })
                        ->pluck('po_number')->toArray();
                    
                        $pfiPoNumbers = implode(", ", $PONumbers); 
                        
                        $PfiItemPos = PfiItemPurchaseOrder::where('pfi_id', $pfiId)->where('pfi_item_id', $parentPfiItemId)
                                            ->pluck('purchase_order_id')->toArray();
                        $PO_with_payment_status = [];
                        $PO_with_payment_initiated_status = [];
                        $PO_with_latest_released_date = [];
                        foreach($PfiItemPos as $PfiItemPo) {
                            $purchaseOrder = PurchasingOrder::find($PfiItemPo);
                            // payment status
                            $paymentStatus = $purchaseOrder->payment_status ?  $purchaseOrder->payment_status : 'Not Available';
                            $PO_with_payment_status[] = $purchaseOrder->po_number ." - ". $paymentStatus;

                            // payment initiated status
                            $paymentStatus = $purchaseOrder->payment_initiated_status ?  $purchaseOrder->payment_initiated_status : 'Not Available';
                            $PO_with_payment_initiated_status[] = $purchaseOrder->po_number ." - ". $paymentStatus;
                                // released date
                            $latestSupplierAccount = SupplierAccountTransaction::Select('purchasing_order_id','payment_released_date','transaction_type')
                                                    ->where('purchasing_order_id', $PfiItemPo)
                                                    ->whereNot('Transaction_type','Rejected')
                                                    ->latest('payment_released_date')
                                                    ->first();
                            if($latestSupplierAccount && $latestSupplierAccount->payment_released_date) {
                                $PO_with_latest_released_date[] = $purchaseOrder->po_number ." - ".
                                Carbon::parse($latestSupplierAccount->payment_released_date)->format('d-M-Y') ?? '';
                            }
                        }
                        $payment_status = implode(", ", $PO_with_payment_status);
                        $payment_initiated_status = implode(", ", $PO_with_payment_initiated_status);
                        $released_date = implode(", ", $PO_with_latest_released_date);

                    return [
                        'PFI ID' => $data->pfi->id ?? '',
                        'PFI Item Code' => $data->code ?? '',
                        'LOI Item Code' => $loiItemCode ?? '',
                        'PFI Date' => Carbon::parse($data->pfi->pfi_date)->format('d-m-Y'),
                        'PFI Number' => $data->pfi->pfi_reference_number,
                        'PO Number' => $pfiPoNumbers ?? '',
                        'Payment Status' => $payment_status ?? '',
                        'Payment Initiated Status' => $payment_initiated_status ?? '',
                        'Payment Released Date' => $released_date ?? '',
                        'Customer Name' => $data->pfi->customer->name ?? '',
                        'Country' => $data->pfi->country->name ?? '',
                        'Vendor' => $data->pfi->supplier->supplier ?? '',
                        'Currency' => $data->pfi->currency ?? '',
                        'Steering' => $data->masterModel->steering,
                        'Brand' => $data->masterModel->modelLine->brand->brand_name ?? '',
                        'Model Line' => $data->masterModel->modelLine->model_line,
                        'Model' => $data->masterModel->model,
                        'SFX' => $data->masterModel->sfx,
                        'PFI Quantity' => $data->pfi_quantity,
                        'Unit Price' => $data->unit_price,
                        'Total Price' => $data->total_price,
                        'PFI Amount' => $data->pfi->amount,
                        'Comment' => $data->pfi->comment ?? '',
                       
                    ];
                });
            }

            if (request()->ajax()) {
                return DataTables::of($data)
                    ->addIndexColumn()    
                    ->editColumn('unit_price', function($query) {
                        return number_format($query->unit_price);
                    })
                    ->editColumn('amount', function($query) {
                        return number_format($query->pfi->amount);
                    })
                   ->addColumn('pfi_date', function($query) {
                        if($query->pfi->pfi_date) {
                            return Carbon::parse($query->pfi->pfi_date)->format('d M Y');
                        }
                        return "";
                    })
                    ->addColumn('loi_item_code', function($query) {
                        $pfiId = $query->pfi->id;
                        $parentPfiItemId = $query->id;
                        $LOICodes = LetterOfIndentItem::whereHas('pfiItems', function($params) use($pfiId,$parentPfiItemId) {
                            $params->where('pfi_id', $pfiId)->where('parent_pfi_item_id', $parentPfiItemId);
                        })
                        ->pluck('code')->toArray();
                    
                        return implode(", ", $LOICodes);  
                    })
                    ->addColumn('po_number', function($query) {
                        $pfiId = $query->pfi->id;
                        $parentPfiItemId = $query->id;
                        $PONumbers = PurchasingOrder::whereHas('PfiItemPurchaseOrders', function($params) use($pfiId,$parentPfiItemId) {
                            $params->where('pfi_id', $pfiId)->where('pfi_item_id', $parentPfiItemId);
                        })
                        ->pluck('po_number')->toArray();
                    
                        return implode(", ", $PONumbers);  
                    })
                   
                    ->addColumn('payment_status', function($query) {
                        $pfiId = $query->pfi->id;
                        $parentPfiItemId = $query->id;
                        $PfiItemPos = PfiItemPurchaseOrder::where('pfi_id', $pfiId)->where('pfi_item_id', $parentPfiItemId)
                        ->pluck('purchase_order_id')->toArray();
                        $PO_with_payment_status = [];
                        foreach($PfiItemPos as $PfiItemPo) {
                            $purchaseOrder = PurchasingOrder::find($PfiItemPo);
                            $paymentStatus = $purchaseOrder->payment_status ?  $purchaseOrder->payment_status : 'Not Available';
                            $PO_with_payment_status[] = $purchaseOrder->po_number ." - ". $paymentStatus;
                        }
                        return implode(", ", $PO_with_payment_status);  
                    })
                    ->addColumn('payment_initiated_status', function($query) {
                        $pfiId = $query->pfi->id;
                        $parentPfiItemId = $query->id;
                        $PfiItemPos = PfiItemPurchaseOrder::where('pfi_id', $pfiId)->where('pfi_item_id', $parentPfiItemId)
                        ->pluck('purchase_order_id')->toArray();
                        $PO_with_payment_status = [];
                        foreach($PfiItemPos as $PfiItemPo) {
                            $purchaseOrder = PurchasingOrder::find($PfiItemPo);
                            $paymentStatus = $purchaseOrder->payment_initiated_status ?  $purchaseOrder->payment_initiated_status : 'Not Available';
                            $PO_with_payment_status[] = $purchaseOrder->po_number ." - ". $paymentStatus;
                        }
                        return implode(", ", $PO_with_payment_status);  
                    })
                    ->addColumn('released_date', function($query) {
                        $pfiId = $query->pfi->id;
                        $parentPfiItemId = $query->id;
                        $PfiItemPos = PfiItemPurchaseOrder::where('pfi_id', $pfiId)->where('pfi_item_id', $parentPfiItemId)
                                                ->pluck('purchase_order_id')->toArray();
                        $PO_with_latest_released_date = [];
                        foreach($PfiItemPos as $PfiItemPo) {
                            $purchaseOrder = PurchasingOrder::find($PfiItemPo);
                            $supplierAccounttransactions = SupplierAccountTransaction::Select('purchasing_order_id','payment_released_date','transaction_type')
                            ->where('purchasing_order_id', $PfiItemPo)
                            ->whereNot('Transaction_type','Rejected')
                            ->latest('payment_released_date');
                            
                            $latestSupplierAccount = $supplierAccounttransactions->first();
                            if($latestSupplierAccount && $latestSupplierAccount->payment_released_date) {
                                $PO_with_latest_released_date[] =  Carbon::parse($latestSupplierAccount->payment_released_date)->format('d-M-Y') .
                                " (".$purchaseOrder->po_number.")";

                            }
                        }
                        if($PO_with_latest_released_date) {
                            $released_date =  implode("<br>", $PO_with_latest_released_date); 

                            if($released_date && $supplierAccounttransactions->count() > 1) {
                                 return  '<button class="btn btn-sm btn-danger">'.$released_date.'</button>';
                            }else{
                                return '<button class="btn btn-sm btn-success">'.$released_date.'</button>';
                            }
                        }

                        return "Payment Not Released";
                        
                        
                    })
                    ->editColumn('total_price', function($query) {
                        return number_format($query->total_price);
                    })
                    ->rawColumns(['pfi_date','loi_item_code','total_price','po_number','payment_initiated_status','payment_status',
                    'released_date'])
                    ->toJson();
                }
            
            return view('pfi.pfi-items.index', compact('suppliers','masterModels','customers','countries','modelLines','brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
   
        (new UserActivityController)->createActivity('Open PFI Create Page');

        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();
        $masterModels = MasterModel::with('modelLine')->select('id','master_model_line_id','model','sfx')
                                      ->groupBy('model')->get();
         $customers = Clients::where('is_demand_planning_customer', true)->select('id','name')->groupBy('name')->get();
 
         return view('pfi.create', compact('suppliers','masterModels','customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('New PFI Created');

        $request->validate([
            'pfi_reference_number' => 'required',
            'pfi_date' => 'required',
            'amount'  => 'required',
            'country_id'  => 'required',
            'client_id'  => 'required',
            'supplier_id' =>'required',
            'file' => 'mimes:pdf,png,jpeg,jpg'
        ]);

        DB::beginTransaction();
        
        $pfi = new PFI();

        $pfi->pfi_reference_number = $request->pfi_reference_number;
        $pfi->pfi_date = $request->pfi_date;
        $pfi->amount = $request->amount;
        $pfi->created_by = Auth::id();
        $pfi->comment = $request->comment;
        $pfi->status = PFI::PFI_STATUS_NEW;
        $pfi->delivery_location = $request->delivery_location;
        $pfi->currency = $request->currency;
        $pfi->supplier_id = $request->supplier_id;
        $pfi->country_id = $request->country_id;
        $pfi->client_id = $request->client_id;
        $pfi->payment_status = PFI::PFI_PAYMENT_STATUS_UNPAID;

        $destinationPath = 'PFI_document_withoutsign/';
        if ($request->has('file'))
        {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'MILELE - '.$request->pfi_reference_number."_".time().'.pdf';
            $file->move($destinationPath, $fileName);
            $pfi->pfi_document_without_sign = $fileName;
        }

        $pfi->save();
        foreach($request->PfiItem as $key => $PfiData) {
                $model = $PfiData['model'];               
                $sfx = $PfiData['sfx'];
                $pfiQuantity = $PfiData['parent_pfi_quantity'];
                $unitPrice = $PfiData['unit_price'];

                $masterModel = MasterModel::where('model', $model)->where('sfx', $sfx)->orderBy('model_year','DESC')->first();
                $latestRow = PfiItem::withTrashed()->orderBy('id', 'desc')->first();
                    $length = 6;
                    $offset = 2;
                    $prefix = "P ";
                    if($latestRow){
                        $latestUUID =  $latestRow->code;
                        $latestUUIDNumber = substr($latestUUID, $offset, $length);
                        
                        $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                        $code =  $prefix.$newCode;
                    }else{
                        $code = $prefix.'001';
                    }
                    // create parent row
                    $pfiItemParentRow = new PfiItem();
                    $pfiItemParentRow->pfi_id = $pfi->id;
                    
                    $pfiItemParentRow->code = $code;
                    $pfiItemParentRow->master_model_id = $masterModel->id ?? '';
                    $pfiItemParentRow->pfi_quantity = $pfiQuantity;
                    $pfiItemParentRow->unit_price = $unitPrice;
                    $pfiItemParentRow->created_by = Auth::id();
                    $pfiItemParentRow->is_parent = true;
                    $pfiItemParentRow->save();
                    $parentId = $pfiItemParentRow->id;
                     if(array_key_exists("loi_item", $PfiData)) {
                        foreach($PfiData['loi_item'] as $keyValue => $loiItem) {
                            $latestItem = PfiItem::withTrashed()->orderBy('id', 'desc')->first();
                            if($latestItem){
                                $latestUUID =  $latestItem->code;
                                $latestUUIDNumber = substr($latestUUID, $offset, $length);
                                
                                $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                                $latestCode =  $prefix.$newCode;
                            }else{
                                $latestCode = $prefix.'001';
                            }

                            $LOIItem = LetterOfIndentItem::find($loiItem);
            
                            $pfiItemRow = new PfiItem();
                            $pfiItemRow->pfi_id = $pfi->id;
                            $pfiItemRow->loi_item_id = $loiItem;
                            $pfiItemRow->code = $latestCode;
                            $pfiItemRow->master_model_id = $LOIItem->masterModel->id ?? '';
                            $pfiItemRow->pfi_quantity =  $PfiData['pfi_quantity'][$keyValue];
                            $pfiItemRow->unit_price = $unitPrice;
                            $pfiItemRow->created_by = Auth::id();
                            $pfiItemRow->parent_pfi_item_id = $parentId;
            
                            $pfiItemRow->save();
                            
                        }
                        
                    }
        }
        /// get any parent item 
        $pfiItemLatest =  PfiItem::where('pfi_id', $pfi->id)
                            ->where('is_parent', false)
                            ->first();
        $pfi->is_toyota_pfi = 0;
        if($pfiItemLatest) {
            // only toyota PFI have child , so if child exist it will be toyota PO
            $pfi->is_toyota_pfi = 1;
        }
        $pfi->save();
        
        DB::commit();

        $supplier = Supplier::find($request->supplier_id);
        if($supplier->supplier == 'CPS Middle East Automobiles Trading FZE' && !$request->has('file')) {
            return redirect()->route('pfi.pfi-document',['id' => Crypt::encrypt($pfi->id),'type' => 'NEW']);
        }

        return redirect()->route('pfi.index')->with('success', 'PFI created Successfully');
       
    }
    public function generatePFIDocument(Request $request) {
        $pfiId = Crypt::decrypt($request->id);
        $pfi = PFI::find($pfiId);
        $pfiItems = PfiItem::where('is_parent', true)->where('pfi_id', $pfi->id)->get();
        $pdfFile = PDF::loadView('pfi.pfi_document_template_download', compact('pfi','pfiItems'));
        $fileName = 'MILELE - '.$pfi->pfi_reference_number;
        
        if($request->download == 1) {
            return $pdfFile->download($fileName.'.pdf',['compress' => true]);
        }else{
            $fileName = $fileName."_".time().'.pdf';

            if($request->type == 'EDIT' && $pfi->pfi_document_without_sign) {
                $destinationPath = 'New_PFI_document_without_sign';
                if(!\Illuminate\Support\Facades\File::isDirectory($destinationPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }
                if (File::exists(public_path('New_PFI_document_without_sign/'.$pfi->new_pfi_document_without_sign))) {
                    File::delete(public_path('New_PFI_document_without_sign/'.$pfi->new_pfi_document_without_sign));
                }

                $filePath = public_path('New_PFI_document_without_sign/'.$fileName);
                file_put_contents($filePath, $pdfFile->output());
               
                $pfi->new_pfi_document_without_sign = $fileName;

            }else{
                $filedestination = 'PFI_document_withoutsign';
                if(!\Illuminate\Support\Facades\File::isDirectory($filedestination)) {
                    \Illuminate\Support\Facades\File::makeDirectory($filedestination, $mode = 0777, true, true);
                }
                $filePath = public_path('PFI_document_withoutsign/'.$fileName);
                file_put_contents($filePath, $pdfFile->output());
                $pfi->pfi_document_without_sign = $fileName;
            }
            $pfi->save();
        }

        return view('pfi.pfi_document_template', compact('pfi','pfiItems'));
        
    }
    public function uniqueCheckPfiReferenceNumber(Request $request) {

        $pfi = PFI::select('id','pfi_reference_number','pfi_date')
                ->where('pfi_reference_number', $request->pfi_reference_number)
                ->whereYear('pfi_date', Carbon::now()->year)
                ->whereNotIn(\DB::raw('MONTH(pfi_date)'), [12, 1]);
                
        if($request->pfi_id) {

            $pfi = $pfi->whereNot('id', $request->pfi_id);
        }
        $pfi = $pfi->first();

        if($pfi) {
            return response(true);
        }else{
            return response(false);
        }
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
    public function edit(string $id, Request $request)
    {
        $pfi = PFI::find($id);
        (new UserActivityController)->createActivity('Open PFI Edit Page');

        $suppliers = Supplier::with('supplierTypes')
        ->whereHas('supplierTypes', function ($query) {
            $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
        })
        ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
        ->get();
        $masterModels = MasterModel::with('modelLine')->select('id','master_model_line_id','model','sfx')
                                    ->groupBy('model')->get();
        $customers = Clients::where('is_demand_planning_customer', true)->select('id','name')->groupBy('name')->get();
        $client_id = $pfi->client_id;
        $customerCountries = Country::with('clientCountries')
                            ->whereHas('clientCountries', function($query) use($client_id) {
                                $query->where('client_id', $client_id);
                            })->get();
        $pfi->isCreatedPO = 0;  
        $pfiPo = PfiItemPurchaseOrder::where('pfi_id',$pfi->id)->first();  
        if($pfiPo) {
            $pfi->isCreatedPO = 1;  
        }
        $parentPfiItems = PfiItem::where('pfi_id', $pfi->id)->where('is_parent', true)->orderBy('id','ASC')->get();

        foreach($parentPfiItems as $parentPfiItem) {
            $parentPfiItem->sfxLists =  MasterModel::where('model', $parentPfiItem->masterModel->model)->groupBy('sfx')->pluck('sfx');
        
            $parentPfiItem->totalAmount = $parentPfiItem->pfi_quantity * $parentPfiItem->unit_price;
            $parentPfiItem->childPfiItems = PfiItem::where('pfi_id', $pfi->id)->where('is_parent', false)
                                            ->where('parent_pfi_item_id', $parentPfiItem->id)->orderBy('id','ASC')->get();

            
            $masterModel = MasterModel::where('model', $parentPfiItem->masterModel->model)
                                ->where('sfx', $parentPfiItem->masterModel->sfx)
                                ->first();
            $parentPfiItem->exactMatches = MasterModel::where('model', $parentPfiItem->masterModel->model)
                                                        ->where('sfx', $parentPfiItem->masterModel->sfx)->pluck('id')->toArray();
            // confirm need to except cancelled PO
            
            $parentPfiItem->is_brand_toyota = 1;
            $brandName = "TOYOTA";
            if(strtoupper($masterModel->modelLine->brand->brand_name) !== $brandName)
            {
                $parentPfiItem->is_brand_toyota = 0;
            }
            
            foreach($parentPfiItem->childPfiItems as $childItem)
            {                     
                $childItem->LOIItemCodes = letterOfIndentItem::whereHas('pfiItems', function($query)use($id,$parentPfiItem){
                        $query->where('pfi_id', $id)
                        ->where('parent_pfi_item_id', $parentPfiItem->id);
                })->get();

                if($childItem->letterOfIndentItem) {
                    $childItem->remainingQuantity = $childItem->letterOfIndentItem->quantity - $childItem->letterOfIndentItem->utilized_quantity;
                    if($childItem->letterOfIndentItem->utilized_quantity <= 0){
                        // if not utilized remaining qty
                        $childItem->maximumPfiQty = $childItem->remainingQuantity;

                    }else{
                        // if fully utilized max will be pfi qty added
                        // if any qty utilized max will be pfi qty added + remaining qty
                        $childItem->maximumPfiQty = $childItem->remainingQuantity + $childItem->pfi_quantity;

                    }
                }
            }
        }

     return view('pfi.edit', compact('suppliers','masterModels','customers','pfi','customerCountries','parentPfiItems'));
    }
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Updated PFI Details');

        $request->validate([
            'pfi_reference_number' => 'required',
            'pfi_date' => 'required',
            'amount'  => 'required',
            'country_id'  => 'required',
            'client_id'  => 'required',
            'supplier_id' =>'required',
            'file' => 'mimes:pdf,png,jpeg,jpg'
        ]);
        // return $request->all();
        try{
            DB::beginTransaction();
            $pfi = PFI::findOrFail($id);

            $pfi->pfi_reference_number = $request->pfi_reference_number;
            $pfi->pfi_date = $request->pfi_date;
            $pfi->amount = $request->amount;
            $pfi->updated_by = Auth::id();
            $pfi->comment = $request->comment;
            $pfi->status = PFI::PFI_STATUS_NEW;
            $pfi->delivery_location = $request->delivery_location;
            $pfi->currency = $request->currency;
            $pfi->supplier_id = $request->supplier_id;
            $pfi->country_id = $request->country_id;
            $pfi->client_id = $request->client_id;
            $pfi->payment_status = PFI::PFI_PAYMENT_STATUS_UNPAID;

            if ($request->has('file'))
            {
                if (File::exists(public_path('New_PFI_document_without_sign/'.$pfi->new_pfi_document_without_sign))) {
                    File::delete(public_path('New_PFI_document_without_sign/'.$pfi->new_pfi_document_without_sign));
                }
                $destinationPath = 'New_PFI_document_without_sign';
            
                $fileName = 'MILELE - '.$request->pfi_reference_number."_".time().'.pdf';
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $pfi->pfi_document_without_sign = $fileName;
            }

            $pfi->save();
            $pfiItemRowParentId = [];
            $alreadyAddedRows =  PfiItem::where('pfi_id', $pfi->id)->pluck('id')->toArray();
            $updatedRows = [];
            // Same LOI Item can be add for different parents
            foreach($request->PfiItem as $key => $PfiData) {
                $model = $PfiData['model'];               
                $sfx = $PfiData['sfx'];
                $pfiQuantity = $PfiData['parent_pfi_quantity'];
                $unitPrice = $PfiData['unit_price'];

                $masterModel = MasterModel::where('model', $model)->where('sfx', $sfx)->orderBy('model_year','DESC')->first();
                    // create parent row
                    $pfiItemParentRow = PfiItem::where('master_model_id', $masterModel->id)
                                        ->where('is_parent', true)
                                        ->where('pfi_id',$pfi->id)->first();
                    if(!$pfiItemParentRow) {
                        $pfiItemParentRow = new PfiItem();
                        
                        $latestRow = PfiItem::withTrashed()->orderBy('id', 'desc')->first();
                                $length = 6;
                                $offset = 2;
                                $prefix = "P ";
                                if($latestRow){
                                    $latestUUID =  $latestRow->code;
                                    $latestUUIDNumber = substr($latestUUID, $offset, $length);
                                    
                                    $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                                    $code =  $prefix.$newCode;
                                }else{
                                    $code = $prefix.'001';
                                }
                                // add new code
                        $pfiItemParentRow->code = $code;
                    }
                    $updatedRows[] = $pfiItemParentRow->id;
                    $pfiItemParentRow->pfi_id = $pfi->id;
                    
                    $pfiItemParentRow->master_model_id = $masterModel->id ?? '';
                    // chcek if qty updated
                    if($pfiItemParentRow->pfi_quantity > $pfiQuantity) {
                        // qty decresed => add to vehicle log and remove from vehicle table 
                    }else if($pfiItemParentRow->pfi_quantity < $pfiQuantity) {
                        // qty increased => add to vehicle log and vehicle table
                    }
                    
                    $pfiItemParentRow->pfi_quantity = $pfiQuantity;
                    $pfiItemParentRow->unit_price = $unitPrice;
                    $pfiItemParentRow->created_by = Auth::id();
                    $pfiItemParentRow->is_parent = true;

                    $pfiItemParentRow->save();

                    $parentId = $pfiItemParentRow->id;
                    if(array_key_exists("loi_item", $PfiData)) {
                        foreach($PfiData['loi_item'] as $keyValue => $loiItem) {
                            $childPfiQuantity = $PfiData['pfi_quantity'][$keyValue];
                            $pfiItemRow = PfiItem::where('loi_item_id', $loiItem)
                                        ->where('is_parent', false)
                                        ->where('parent_pfi_item_id', $parentId)
                                        ->where('pfi_quantity', $childPfiQuantity)
                                        ->where('pfi_id', $pfi->id)->first();
                            if(!$pfiItemRow) {
                                $pfiItemRow = new PfiItem();

                                $latestData = PfiItem::withTrashed()->orderBy('id', 'desc')->first();
                                $length = 6;
                                $offset = 2;
                                $prefix = "P ";
                                if($latestData){
                                    $latestUUID =  $latestData->code;
                                    $latestUUIDNumber = substr($latestUUID, $offset, $length);
                                    
                                    $newCode =  str_pad($latestUUIDNumber + 1, 3, 0, STR_PAD_LEFT);
                                    $latestCode =  $prefix.$newCode;
                                }else{
                                    $latestCode = $prefix.'001';
                                }
                                // add code if new item add
                                $pfiItemRow->code = $latestCode;
                                
                            }
                            $updatedRows[] = $pfiItemRow->id;
                            
                            $LOIItem = LetterOfIndentItem::find($loiItem);
        
                            $pfiItemRow->pfi_id = $pfi->id;
                            $pfiItemRow->master_model_id = $LOIItem->masterModel->id ?? '';
                            $pfiItemRow->loi_item_id = $loiItem;
                            $pfiItemRow->pfi_quantity =  $childPfiQuantity;
                            $pfiItemRow->unit_price = $unitPrice;
                            $pfiItemRow->created_by = Auth::id();
                            $pfiItemRow->parent_pfi_item_id = $parentId;
                            $pfiItemRow->save();
                        }
                    }
        
            }

            $deletedRows = array_diff($alreadyAddedRows,$updatedRows);
            PfiItem::whereIn('id', $deletedRows)->delete();

            $pfiItemLatest =  PfiItem::where('pfi_id', $pfi->id)
                        ->where('is_parent', false)
                        ->first();
            $pfi->is_toyota_pfi = 0;
                if($pfiItemLatest) {
                    // only toyota PFI have child , so if child exist it will be toyota PO
                    $pfi->is_toyota_pfi = 1;
                }
            $pfi->save();

            // if PFI has PO and if pfi is toyota
            $pfiPo = PfiItemPurchaseOrder::where('pfi_id',$pfi->id)->first();  
            if($pfiPo && $pfi->is_toyota_pfi == true) {
                // po exit chcek for update
            // chcek the items are same or not
            $parentpfiItems = PfiItemPurchaseOrder::where('pfi_id', $pfi->id)->get();
            foreach($parentpfiItems as $parentpfiItem) {
                $IseachItemExist =  PfiItem::where('pfi_id', $parentpfiItem->pfi_id)->where('parent_pfi_item_id', $parentpfiItem->pfi_item_id)
                                                ->where('master_model_id', $parentpfiItem->master_model_id)->first();
                if($IseachItemExist) {
                    // check for price update
                }
            }

            }

        DB::commit();
        
        $supplier = Supplier::find($request->supplier_id);
            if($supplier->supplier == 'CPS Middle East Automobiles Trading FZE' && !$request->has('file')){
                return redirect()->route('pfi.pfi-document',['id' => Crypt::encrypt($pfi->id),'type' => 'EDIT']);
            }else{
                return redirect()->route('pfi.index')->with('message', 'PFI Updated Successfully');
            }
    }catch (\Exception $e){
        return $e->getMessage();
    }

    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pfi = PFI::find($id);
        DB::beginTransaction();
        // make pfi creation reverse when it is deleting
       
        $pfi->pfiItems()->delete();
        $pfi->deleted_by = Auth::id();
        $pfi->save();
        $pfi->delete();

        (new UserActivityController)->createActivity('Deleted PFI Sucessfully.');

        DB::commit();

        return response(true);

    }
   
    
    public function relaesedAmountUpdate(Request $request) {
        (new UserActivityController)->createActivity('PFI released amount updated.');

        $pfi = PFI::find($request->pfi_id);
        $pfi->released_amount = $request->released_amount;
        $pfi->released_date = $request->released_date;
        $pfi->updated_by = Auth::id();
        $pfi->save();
        return response($pfi);
    }
    public function getLOIItemCode(Request $request) {
       $data = [];

       $parentModel = MasterModel::where('model', $request->model)
                            ->where('sfx', $request->sfx)->first();
        $parentModelsteering = $parentModel->steering;
        $parentModelLine = $parentModel->master_model_line_id;

       $loiItems = LetterOfIndentItem::with('masterModel','LOI')
                ->whereColumn('utilized_quantity', '<', 'quantity')
                ->whereHas('masterModel', function($query)use($parentModelLine, $parentModelsteering) {
                    $query->where('steering', $parentModelsteering);
                })
                ->whereHas('LOI', function($query)use($request) {
                        $query->select('client_id','status','id','is_expired','country_id')
                        ->where('client_id', $request->client_id)
                        ->where('country_id', $request->country_id)
                        ->whereIn('status', [LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL, LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED,
                            LetterOfIndent::LOI_STATUS_TTC_APPROVED,LetterOfIndent::LOI_STATUS_WAITING_FOR_TTC_APPROVAL])
                        ->where('is_expired', false);
                });
                
            $model_line = 'HIACE';
            $parentModelLine = strtoupper($parentModel->modelLine->model_line);
            if(str_contains($parentModelLine, $model_line)){
                $loiItems = $loiItems->whereHas('masterModel.modelLine', function($query)use($model_line){
                    $query->where('model_line', 'LIKE', '%'. $model_line .'%');
                });
            }else{
                $loiItems = $loiItems->whereHas('masterModel', function($query)use($parentModel){
                    $query->where('master_model_line_id', $parentModel->master_model_line_id); 
                });
            }              
            
            if($request->selectedLOIItemIds) {
                $loiItems = $loiItems->whereNotIn('id', $request->selectedLOIItemIds);            
            }

        $data['codes'] = $loiItems->get();
        $parentModels = MasterModel::where('model', $request->model)
                                ->where('sfx', $request->sfx)
                                ->pluck('id')->toArray(); 
        $data['parentCodes'] = $loiItems->whereIn('master_model_id', $parentModels)->pluck('id')->toArray();
        if($request->page == 'Edit') {
            return $data;
        }
        return response($data);
    }
  
    public function getModels(Request $request) {
                
        $data = MasterModel::orderBy('id','DESC');           
            if($request->selectedModelIds) {
                $restrictedModelIds = [];
                foreach($request->selectedModelIds as $selectedModelId){
                    $masterModel = MasterModel::find($selectedModelId);
                    $possibleModels = MasterModel::where('model', $masterModel->model)
                                            ->where('sfx', $masterModel->sfx)
                                            ->get();
                    foreach($possibleModels as $possibleModel) {
                        $restrictedModelIds[] = $possibleModel->id;
                    }                  
                }
                if($restrictedModelIds) {
                    $data = $data->whereNotIn('id', $restrictedModelIds);
                }
            }
           
            $data = $data->groupBy('model')->get();
            if($request->page == 'Edit') {
               return $data; 
            }
            return response($data);
       
    }
    public function getMasterModel(Request $request) {

        $masterModel = MasterModel::where('model', $request->model)
                        ->where('sfx', $request->sfx)
                        ->orderBy('id','DESC')
                        ->first();
        $supplier = Supplier::find($request->supplier_id);
        if($supplier && $masterModel) {
            if($supplier->is_MMC == true) {
                $price = $masterModel->amount_belgium > 0 ?  $masterModel->amount_belgium : 0;
            }else if($supplier->is_AMS == true || $supplier->supplier == 'CPS Middle East Automobiles Trading FZE') {
                $price = $masterModel->amount_uae > 0 ? $masterModel->amount_uae : 0;
            }else{
                $price = 0;
            }
          $data['unit_price'] = $price;
        }   

        $data['master_model_id'] =  $masterModel->id;     
    
        return response($data);
    }
    public function getLOIItemDetails(Request $request) {
        $data = [];
        $supplier = Supplier::find($request->supplier_id);
        $loiItem = LetterOfIndentItem::find($request->loi_item_id);
          
        if($loiItem) {
            $remianingQty = $loiItem->quantity - $loiItem->utilized_quantity;
            $data['remaining_quantity'] = $remianingQty;
        }


        $isLOIItemPfiExist = PfiItem::with('pfi','letterOfIndentItem')
                                    ->whereHas('pfi', function($query) use($request){
                                        $query->where('client_id', $request->client_id)
                                            ->where('country_id', $request->country_id);
                                    })
                                    ->where('loi_item_id', $request->loi_item_id);
        if($request->pfi_id) {
            $isLOIItemPfiExist->whereNot('pfi_id', $request->pfi_id);
        }

        $data['isLOIItemPfiExist'] =   $isLOIItemPfiExist->get();

        return response($data);
    }

    public function getCustomerCountries(Request $request) {

        $countries = Country::with('clientCountries')
            ->whereHas('clientCountries', function($query) use($request){
            $query->where('client_id', $request->client_id);
            })->get();

        return response($countries);

    }
    public function getBrand(Request $request){
        $masterModel = MasterModel::where('model', $request->model)
                        ->where('sfx', $request->sfx)
                        ->first();
        $data = 0;
        $brandName = "TOYOTA";
        if(strtoupper($masterModel->modelLine->brand->brand_name) == $brandName)
        {
            $data = 1;
        }
        return response($data);
    }

    public function getPfiBrand(Request $request)
    {
        $masterModels = $request->master_model_ids;
        $brands = [];
        foreach($masterModels as $masterModel) {
            $model = MasterModel::find($masterModel);
            $brands[] = strtoupper($model->modelLine->brand->brand_name);
        }
       $data = [];
       $data['is_pfi_valid_brand'] = true;
       $data['is_toyota_pfi'] = false;
        // check array contains toyota models
        if(in_array("TOYOTA", $brands)) {
            if(count(array_unique($brands)) === 1) {
                info("all model are toyota");
                // all model is toyota
                $data['is_pfi_valid_brand'] = true;
                $data['is_toyota_pfi'] = true;
            }else{

                // not all model are toyota
                info("not all toyota model");
                $data['is_pfi_valid_brand'] = false;
            }
        }
       
        return response($data);
    }
   

     // document sealing
        // if($request->has('file')) {
        //     try {
            
        //         $pdf = new Fpdi();
        //         $pageCount = $pdf->setSourceFile($destinationPath.'/'.$fileName);
    
        //         for ($i=1; $i <= $pageCount; $i++)
        //         {
        //             $pdf->AddPage();
        //             $tplIdx = $pdf->importPage($i);
        //             $pdf->useTemplate($tplIdx);
        //             if($i == $pageCount) {
        //                 $pdf->Image('milele_seal.png', 80, 230, 50,35);
        //             }
        //         }
    
        //         $signedFileName = 'signed_'.time().'.'.$extension;
        //         $directory = public_path('PFI_Document_with_sign');
        //         \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
        //         $pdf->Output($directory.'/'.$signedFileName,'F');
        //         $pfi->pfi_document_with_sign = $signedFileName;
        //     }catch (\Exception $e) {
    
        //         return redirect()->back()->with('error', $e->getMessage());
        //     }
            // $pfi->save();
        // }

}
