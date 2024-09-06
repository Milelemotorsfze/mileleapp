<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LetterOfIndent;
use App\Models\MasterModel;
use App\Models\LetterOfIndentItem;
use App\Models\PFI;
use App\Models\PfiItem;
use App\Models\Supplier;
use App\Models\Clients;
use App\Models\Country;
use App\Models\ClientCountry;
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

class PFIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open PFI List Section');

        $data = PFI::orderBy('updated_at','DESC')->with([
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
                    })                    ->editColumn('updated_at', function($query) {
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
                    ->addColumn('action', function(PFI $pfi) {
                        $parentPfiItems =  PfiItem::where('is_parent', true)->where('pfi_id', $pfi->id)->get();
                        foreach($parentPfiItems as $item) {
                            $pfiQuantity = PfiItem::where('pfi_id', $pfi->id)->where('parent_pfi_item_id', $item->id)
                                    ->sum('pfi_quantity');

                            $pfi_quantity = $pfiQuantity + $item->pfi_quantity;
                            $item->quantity = $pfi_quantity;
                        }
                        return view('pfi.action',compact('pfi','parentPfiItems'));
                    })
                    ->rawColumns(['action'])
                    ->toJson();
                }

            
            return view('pfi.index');
    }
    public function PFIItemList(Request $request) {
        (new UserActivityController)->createActivity('Open PFI Item List Section');

        $data = PfiItem::where('is_parent', true)->orderBy('updated_at','DESC')->with([
            'pfi' => function ($query) {
                $query->select('id','supplier_id','country_id','client_id','pfi_reference_number',
                'currency','amount','comment','pfi_date');
            },
            'letterOfIndentItem' => function ($query) {
                $query->select('id','code','master_model_id','letter_of_indent_id');
            },
            'letterOfIndentItem.LOI' => function ($query) {
                $query->select('id','status');
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
            // return $data->get();
            if($request->export == 'EXCEL') {
                (new UserActivityController)->createActivity('Downloaded PFI Item List');

                $data = $data->get();
              return (new FastExcel($data))->download('PFI-ITEMS.csv', function ($data) {
                $pfiQuantitySum = PfiItem::where('parent_pfi_item_id', $data->id)
                                    ->sum('pfi_quantity');
                $pfiQuantity = $pfiQuantitySum + $data->pfi_quantity;

                    return [
                        'LOI Item Code' => $data->letterOfIndentItem->code ?? '',
                        'LOI Status' => $data->letterOfIndentItem->LOI->status ?? '',
                        'PFI Date' => Carbon::parse($data->pfi->pfi_date)->format('d-m-Y'),
                        'PFI Number' => $data->pfi->pfi_reference_number,
                        'Customer Name' => $data->pfi->customer->name ?? '',
                        'Country' => $data->pfi->country->name ?? '',
                        'Vendor' => $data->pfi->supplier->supplier ?? '',
                        'Currency' => $data->pfi->currency ?? '',
                        'Steering' => $data->masterModel->steering,
                        'Brand' => $data->masterModel->modelLine->brand->brand_name ?? '',
                        'Model Line' => $data->masterModel->modelLine->model_line,
                        'Model' => $data->masterModel->model,
                        'SFX' => $data->masterModel->sfx,
                        'PFI Quantity' => $pfiQuantity,
                        'Unit Price' => $data->unit_price,
                        'Total Price' => $data->unit_price * $pfiQuantity,
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
                    ->addColumn('pfi_quantity', function($query) {
                        $pfiQuantity = PfiItem::where('parent_pfi_item_id', $query->id)
                                    ->sum('pfi_quantity');
                        $quantity = $pfiQuantity + $query->pfi_quantity;
                        return $quantity;
                    })
                    ->addColumn('pfi_date', function($query) {
                        if($query->pfi->pfi_date) {
                            return Carbon::parse($query->pfi->pfi_date)->format('d M Y');
                        }
                        return "";
                       
                    })
                    ->addColumn('loi_item_code', function($query) {
                        return $query->letterOfIndentItem->code ?? '';
                    })
                    ->addColumn('loi_status', function($query) {
                        return $query->letterOfIndentItem->LOI->status ?? '';
                    })
                    ->addColumn('total_price', function($query) {
                        $pfiQuantity = PfiItem::where('parent_pfi_item_id', $query->id)
                        ->sum('pfi_quantity');
                        $quantity = $pfiQuantity + $query->pfi_quantity;
                        $total = $quantity * $query->unit_price;
                        return number_format($total);
                    })
                    ->rawColumns(['pfi_date','pfi_quantity','loi_item_code'])
                    ->toJson();
                }
            
            return view('pfi.pfi-items.index');
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
            'amount'  => 'required',
            'country_id'  => 'required',
            'client_id'  => 'required',
            'supplier_id' =>'required',
            'file' => 'required|mimes:pdf,png,jpeg,jpg'
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
//        $pfi->released_amount = $request->released_amount;
        $pfi->payment_status = PFI::PFI_PAYMENT_STATUS_UNPAID;

        $destinationPath = 'PFI_document_withoutsign';
        // $destination = 'PFI_document_withsign';

        if ($request->has('file'))
        {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $file->move($destinationPath, $fileName);
            $pfi->pfi_document_without_sign = $fileName;
        }

        $pfi->save();
        $pfiItemRowParentId = [];
        foreach($request->PfiItem as $key => $pfiItem) {
            $parentId = NULL;
            foreach($pfiItem['model'] as $keyValue => $model) {
                $model = $pfiItem['model'][$keyValue];               
                $sfx = $pfiItem['sfx'][$keyValue];
                $loiItemId = $pfiItem['loi_item'][$keyValue];
                $pfiQuantity = $pfiItem['pfi_quantity'][$keyValue];
                $unitPrice = $pfiItem['unit_price'][$keyValue];

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
                    // dd($code);
                $pfiItemRow = new PfiItem();
                $pfiItemRow->pfi_id = $pfi->id;
                if($loiItemId != 'NULL') {
                    $pfiItemRow->loi_item_id = $loiItemId;
                }
                $pfiItemRow->code = $code;
                $pfiItemRow->master_model_id = $masterModel->id ?? '';
                $pfiItemRow->pfi_quantity = $pfiQuantity;
                $pfiItemRow->unit_price = $unitPrice;
                $pfiItemRow->created_by = Auth::id();
                $pfiItemRow->save();
                if($keyValue == 0) {
                    $pfiItemRow->is_parent = true;
                    $parentId = $pfiItemRow->id;
                }else{
                    $pfiItemRowParentId[] = $parentId;
                    $pfiItemRow->parent_pfi_item_id = $parentId;
                }
                $pfiItemRow->save();
            }
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
       
        DB::commit();

        return redirect()->route('pfi.index')->with('success', 'PFI created Successfully');
    }
    public function uniqueCheckPfiReferenceNumber(Request $request) {
//         return $request->all();
        $pfi = PFI::select('id','pfi_reference_number','created_at')
                ->where('pfi_reference_number', $request->pfi_reference_number)
                ->whereYear('created_at', Carbon::now()->year)->first();
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
    
    $parentPfiItems = PfiItem::where('pfi_id', $pfi->id)->where('is_parent', true)->orderBy('id','DESC')->get();

    foreach($parentPfiItems as $parentPfiItem) {
        $parentPfiItem->sfxLists =  MasterModel::where('model', $parentPfiItem->masterModel->model)->groupBy('sfx')->pluck('sfx');
        if($parentPfiItem->letterOfIndentItem) {
            $parentPfiItem->remainingQuantity = $parentPfiItem->letterOfIndentItem->quantity - $parentPfiItem->letterOfIndentItem->utilized_quantity;
        }
        $parentPfiItem->totalAmount = $parentPfiItem->pfi_quantity * $parentPfiItem->unit_price;
        $parentPfiItem->childPfiItems = PfiItem::where('pfi_id', $pfi->id)->where('is_parent', false)
                                        ->where('parent_pfi_item_id', $parentPfiItem->id)->orderBy('id','DESC')->get();

        $request['page'] = 'Edit';  
        $request['client_id']  = $pfi->customer->id;
        $request['customer']  = $pfi->customer->id;
        $request['country_id'] = $pfi->country->id;
        $request['model'] = $parentPfiItem->masterModel->model;
        $request['sfx'] = $parentPfiItem->masterModel->sfx;
        $LOIItems =  $this->getLOIItemCode($request);
        $parentPfiItem->LOIItemCodes = $LOIItems['codes'];
        // pass variable to chcek brand : - if is_loi_available => brand -> toyota, else => brand ->suzuki,Hino
        $parentPfiItem->is_loi_available = $LOIItems['is_loi_available'];

        foreach($parentPfiItem->childPfiItems as $childItem)
         {           
                  
            $request['model'] = $childItem->masterModel->model;
            $request['sfx'] = $childItem->masterModel->sfx;
            $LOIItems =  $this->getLOIItemCode($request);
            $childItem->LOIItemCodes = $LOIItems['codes'];
            $childItem->is_loi_available = $LOIItems['is_loi_available'];

            $childItem->masterModels = $this->getChildModels($request);

            $childItem->sfxLists =  MasterModel::where('model', $childItem->masterModel->model)->groupBy('sfx')->pluck('sfx');
            if($childItem->letterOfIndentItem) {
                $childItem->remainingQuantity = $childItem->letterOfIndentItem->quantity - $childItem->letterOfIndentItem->utilized_quantity;
            }
            $childItem->totalAmount =  $childItem->pfi_quantity * $parentPfiItem->unit_price;
        }
    }

     return view('pfi.edit', compact('suppliers','masterModels','customers','pfi','customerCountries','parentPfiItems'));
    }
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // return $request->all();
//        dd($request->all());
        (new UserActivityController)->createActivity('Updated PFI Details');

        $request->validate([
            'pfi_reference_number' => 'required',
            // 'pfi_date' => 'required',
            'amount'  => 'required',
            'country_id'  => 'required',
            'client_id'  => 'required',
            'supplier_id' =>'required',
            'file' => 'mimes:pdf,png,jpeg,jpg'
        ]);

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

        $destinationPath = 'PFI_document_withoutsign';

        if ($request->has('file'))
        {
            if (File::exists(public_path('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign))) {
                File::delete(public_path('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign));
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $file->move($destinationPath, $fileName);
            $pfi->pfi_document_without_sign = $fileName;
        }

        $pfi->save();
        $pfiItemRowParentId = [];
        $alreadyAddedRows =  PfiItem::where('pfi_id', $pfi->id)->pluck('id')->toArray();
        // $pfi->pfiItems()->delete();
        $updatedRows = [];
        foreach($request->PfiItem as $key => $pfiItem) {
            $parentId = NULL;
            foreach($pfiItem['model'] as $keyValue => $model) 
            {
                $model = $pfiItem['model'][$keyValue];               
                $sfx = $pfiItem['sfx'][$keyValue];
                $loiItemId = $pfiItem['loi_item'][$keyValue];
                $pfiQuantity = $pfiItem['pfi_quantity'][$keyValue];
                $unitPrice = $pfiItem['unit_price'][$keyValue];

                $masterModel = MasterModel::where('model', $model)->where('sfx', $sfx)->orderBy('model_year','DESC')->first();
                
                if($masterModel) {
                    $pfiItemRow = PfiItem::where('loi_item_id', $loiItemId)
                                        ->where('pfi_id', $pfi->id)->first();
                    if($pfiItemRow) {
                        $pfiItemRow->is_parent = false;
                        $pfiItemRow->parent_pfi_item_id = NULL;
                        $pfiItemRow->update();
                        $updatedRows[] = $pfiItemRow->id;
                        
                    }else{
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
                        // dd($code);
                            $pfiItemRow = new PfiItem();
                            $pfiItemRow->code = $code;
                    }
                    $pfiItemRow->pfi_id = $pfi->id;
                    if($loiItemId != 'NULL') {
                        $pfiItemRow->loi_item_id = $loiItemId;
                    }
                    
                    $pfiItemRow->master_model_id = $masterModel->id ?? '';
                    $pfiItemRow->pfi_quantity = $pfiQuantity;
                    $pfiItemRow->unit_price = $unitPrice;
                    $pfiItemRow->created_by = Auth::id();
                    $pfiItemRow->save();
                    if($keyValue == 0) {
                        $pfiItemRow->is_parent = true;
                        $parentId = $pfiItemRow->id;
                    }else{
                        $pfiItemRowParentId[] = $parentId;
                        $pfiItemRow->parent_pfi_item_id = $parentId;
                    }    
                }
                
                $pfiItemRow->save();
            }
        }
        $deletedRows = array_diff($alreadyAddedRows,$updatedRows);
        PfiItem::whereIn('id', $deletedRows)->delete();

        DB::commit();

        return redirect()->route('pfi.index')->with('message', 'PFI Updated Successfully');
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
   
    public function paymentStatusUpdate(Request $request, $id) {

        (new UserActivityController)->createActivity('PFI payment status updated.');

        $pfi = PFI::find($id);
        $pfi->payment_status = $request->payment_status;
        $pfi->save();
        return redirect()->back()->with('success', 'Payment Status Updated Successfully.');
    }
    public function relaesedAmountUpdate(Request $request) {
        (new UserActivityController)->createActivity('PFI released amount updated.');

        $pfi = PFI::find($request->pfi_id);
        $pfi->released_amount = $request->released_amount;
        $pfi->released_date = $request->released_date;
        $pfi->save();
        return response($pfi);
    }
    public function getLOIItemCode(Request $request) {
       $data = [];
       $loiItems = LetterOfIndentItem::with('masterModel','LOI')
                // ->whereColumn('utilized_quantity', '>', 'quantity')
                ->whereHas('masterModel', function($query)use($request) {
                    $query->where('model', $request->model)
                    ->where('sfx', $request->sfx);
                })
                ->whereHas('LOI', function($query)use($request) {
                        $query->select('client_id','status','id','is_expired','country_id')
                        ->where('client_id', $request->client_id)
                        ->where('country_id', $request->country_id)
                        ->whereIn('status', [LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL, LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED])
                        ->where('is_expired', false);
                });
               

            if($request->selectedLOIItemIds) {
                
                $loiItems = $loiItems->whereNotIn('id', $request->selectedLOIItemIds);
             
            }

       $masterModel = MasterModel::where('model', $request->model)
                        ->where('sfx', $request->sfx)
                        ->orderBy('model_year','DESC')
                        ->first();
        $data['is_loi_available'] = 'YES';
         $LOINotAvailableBrands = ['HINO','SUZUKI'];

        if($masterModel) {
           if( in_array($masterModel->modelLine->brand->brand_name, $LOINotAvailableBrands) ){
            $data['is_loi_available'] = 'NO';
           }
        }                
        $data['codes'] = $loiItems->get();
       
       $data['master_model_id'] = $masterModel->id;
       
       if($request->page == 'Edit') {
            return $data;
       }
        return response($data);
       
    }
  

    public function getChildModels(Request $request) {
                
             $data = MasterModel::orderBy('id','DESC');
             
             if($request->is_child == 'Yes') {
                $data = $data->with('loiItems.LOI')
                ->whereHas('loiItems.LOI', function($query)use($request){
                    $query->select('client_id','status','id','is_expired','country_id')
                    ->where('client_id', $request->customer)
                    ->where('country_id', $request->country_id)
                    ->whereIn('status', [LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL, LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED])
                    ->where('is_expired', false);
                }); 

             }

            if($request->model && $request->sfx) {
               
                $parentModel = MasterModel::where('model', $request->model)
                                    ->where('sfx', $request->sfx)->first();

               $data = $data->where('steering', $parentModel->steering);
                $model_line = 'HIACE';
                if(str_contains($parentModel->modelLine->model_line, $model_line)){
                    $data = $data->whereHas('modelLine', function($query)use($model_line){
                        $query->where('model_line', 'LIKE', '%'. $model_line .'%');
                    });
                }else{
                    $data = $data->where('master_model_line_id', $parentModel->master_model_line_id); 
                }  
                       
            }
            
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
    public function getLOIItemDetails(Request $request) {
        $data = [];
        $supplier = Supplier::find($request->supplier_id);
        $loiItem = LetterOfIndentItem::find($request->loi_item_id);
        if($supplier && $loiItem) {
            if($supplier->is_MMC == true) {
                $price = $loiItem->masterModel->amount_belgium > 0 ?  $loiItem->masterModel->amount_belgium : 0;
            }else if($supplier->is_AMS == true) {
                $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;
            }else{
                $price = 0;
            }
            $data['unit_price'] = $price;
        }
          
        if($loiItem) {
            $remianingQty = $loiItem->quantity - $loiItem->utilized_quantity;
            $data['remaining_quantity'] = $remianingQty;
        }
       
        return response($data);
    }

    public function getCustomerCountries(Request $request) {

        $countries = Country::with('clientCountries')
            ->whereHas('clientCountries', function($query) use($request){
            $query->where('client_id', $request->client_id);
            })->get();

        return response($countries);

    }
}
