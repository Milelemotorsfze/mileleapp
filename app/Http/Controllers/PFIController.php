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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\PdfReader\Page;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\File;
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
                    ->addColumn('action', function(PFI $pfi) {
                        return view('pfi.action',compact('pfi'));
                    })
                    ->rawColumns(['action'])
                    ->toJson();
                }
            
            return view('pfi.index');
    }
    public function PFIItemList() {
        
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
        // info($request->PfiItem);
        // info("items");
        // foreach($request->PfiItem as $pfiItems) {
        //     foreach($pfiItems['model'] as $keyValue => $value) {
        //         dd($pfiItems['model'][$keyValue]);

        //     }
        // }
           
        // }
        // return $request->all();

        (new UserActivityController)->createActivity('New PFI Created');

        $request->validate([
            // 'pfi_reference_number' => 'required',
            // 'pfi_date' => 'required',
            // 'amount'  => 'required',
            // 'file' => 'required|mimes:pdf'
        ]);

        DB::beginTransaction();
        $pfi = new PFI();

        $pfi->pfi_reference_number = $request->pfi_reference_number;
//        $pfi->pfi_date = $request->pfi_date;
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
                $pfiItemRow = new PfiItem();
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
    public function edit(string $id)
    {
        $pfi = PFI::find($id);
        (new UserActivityController)->createActivity('Open PFI Edit Page');

        $letterOfIndent = LetterOfIndent::findOrFail($pfi->letter_of_indent_id);
        $pfiItems = PfiItem::where('pfi_id', $id)->get();
        // return $pfiItems;
        $suppliers = Supplier::with('supplierTypes')
            ->whereHas('supplierTypes', function ($query) {
                $query->where('supplier_type', Supplier::SUPPLIER_TYPE_DEMAND_PLANNING);
            })
            ->where('status', Supplier::SUPPLIER_STATUS_ACTIVE)
            ->get();
        $totalLOIQuantity = LetterOfIndentItem::select('letter_of_indent_id','quantity')
            ->where('letter_of_indent_id', $pfi->letter_of_indent_id)
            ->sum('quantity');
            $LOIItems = $letterOfIndent->letterOfIndentItems;

        foreach($LOIItems as $LOIItem) {
            $model_line = 'HIACE';
            // chcek if case insensitive check is possible
            $masterModels = MasterModel::with('modelLine')->select('id','master_model_line_id','model','sfx');
            if(str_contains($LOIItem->masterModel->modelLine->model_line, $model_line)){
                $masterModels = $masterModels->whereHas('modelLine', function($query)use($model_line){
                    $query->where('model_line', 'LIKE', '%'. $model_line .'%');
                });
            }else{
                $masterModels =  $masterModels->where('master_model_line_id', $LOIItem->masterModel->master_model_line_id);
            }
            $LOIItem->masterModels =  $masterModels->groupBy('model')->get();
            // corresponding PFI Item

            $pfiItem = PfiItem::where('pfi_id', $id)->where('loi_item_id', $LOIItem->id)->first();
            
            $totalpfiQuantityUsed = PfiItem::select('loi_item_id','pfi_quantity')->where('loi_item_id', $LOIItem->id)
                                                 ->whereNot('pfi_id', $pfi->id)
                                                ->sum('pfi_quantity');

            $alreadyusedQuantity = $LOIItem->utilized_quantity + $totalpfiQuantityUsed;
            $LOIItem->remaining_quantity = $LOIItem->quantity - $alreadyusedQuantity;
           
            if($pfiItem) {
                $LOIItem->pfi_quantity =  $pfiItem->pfi_quantity;
                $LOIItem->unit_price = $pfiItem->unit_price;
                $LOIItem->total_amount = $pfiItem->unit_price * $pfiItem->pfi_quantity;
            }else{
                $LOIItem->pfi_quantity = 0;
                $LOIItem->unit_price =  0;   
                $LOIItem->total_amount = 0;
            }
           
        }
        return view('pfi.edit_new', compact('pfi','LOIItems','letterOfIndent','suppliers','totalLOIQuantity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
//        dd($request->all());
        (new UserActivityController)->createActivity('Updated PFI Details');

        $request->validate([
            'pfi_reference_number' => 'required',
//            'pfi_date' => 'required',
            'amount'  => 'required',
            'file' => 'mimes:pdf'
        ]);

        DB::beginTransaction();
        $pfi = PFI::find($id);

        $pfi->pfi_reference_number = $request->pfi_reference_number;
//        $pfi->pfi_date = Carbon::parse($request->pfi_date)->format('Y-m-d');
        $pfi->amount = $request->amount;
        $pfi->comment = $request->comment;
        $pfi->delivery_location = $request->delivery_location;
        $pfi->currency = $request->currency;
        $pfi->supplier_id = $request->supplier_id;
//        $pfi->released_amount = $request->released_amount;

        $destinationPath = 'PFI_document_withoutsign';
        $destination = 'PFI_document_withsign';

        if ($request->has('file'))
        {
            if (File::exists(public_path('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign))) {
                File::delete(public_path('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign));
            }
            // if (File::exists(public_path('PFI_document_withsign/'.$pfi->pfi_document_with_sign))) {
            //     File::delete(public_path('PFI_document_withsign/'.$pfi->pfi_document_with_sign));
            // }
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $file->move($destinationPath, $fileName);
            $pfi->pfi_document_without_sign = $fileName;

            // $pdf = new Fpdi();
            // $pageCount = $pdf->setSourceFile($destinationPath.'/'.$fileName);

            // for ($i=1; $i <= $pageCount; $i++)
            // {
            //     $pdf->AddPage();
            //     $tplIdx = $pdf->importPage($i);
            //     $pdf->useTemplate($tplIdx);
            //     if($i == $pageCount) {
            //         $pdf->Image('milele_seal.png', 80, 230, 50,35);
            //     }
            // }

            // $signedFileName = 'signed_'.time().'.'.$extension;
            // $directory = public_path('PFI_Document_with_sign');
            // \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
            // $pdf->Output($directory.'/'.$signedFileName,'F');
            // $pfi->pfi_document_with_sign = $signedFileName;
        }

        $pfi->save();

        // delele all pfi item under this pfi
        $pfi->pfiItems()->delete();
        
        // update pfi items
        if($request->pfi_quantities) {
            foreach($request->pfi_quantities as $key => $pfiQuantity) {
                if($pfiQuantity > 0) {
                    $model = $request->models[$key];
                    $sfx = $request->sfx[$key];
                    $masterModel = MasterModel::where('model', $model)->where('sfx', $sfx)->orderBy('model_year','DESC')->first();
                    $pfiItem = new PfiItem();
                    $pfiItem->pfi_id = $pfi->id;
                    $pfiItem->loi_item_id = $request->loi_item_ids[$key];
                    $pfiItem->master_model_id = $masterModel->id ?? '';
                    $pfiItem->pfi_quantity = $pfiQuantity;
                    $pfiItem->unit_price = $request->unit_price[$key];
                    $pfiItem->created_by = Auth::id();
                    $pfiItem->save();

                }
            }
        }

        DB::commit();

        return redirect()->route('pfi.index')->with('message', 'PFI Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pfi = PFI::find($id);
        // $approvedItemsForPFIs = ApprovedLetterOfIndentItem::where('pfi_id', $id)->get();

        DB::beginTransaction();
        // make pfi creation reverse when it is deleting
       
        $pfi->pfiItems()->delete();
        $pfi->delete();
        (new UserActivityController)->createActivity('Deleted PFI Sucessfully.');

        DB::commit();

        return response(true);

    }
    // public function getUnitPrice(Request $request) {
    //     $supplier = Supplier::find($request->supplier_id);
    //             $loiItem = LetterOfIndentItem::find($loiItem->id);

    //             if($supplier->is_MMC == true) {
    //                 $price = $loiItem->masterModel->amount_belgium > 0 ?  $loiItem->masterModel->amount_belgium : 0;
    //             }else if($supplier->is_AMS == true) {
    //                 $price = $loiItem->masterModel->amount_uae > 0 ? $loiItem->masterModel->amount_uae : 0;
    //             }else{
    //                 $price = 0;
    //             }
    //             $data['unit_price'] = $price;
        
    //     return $data;

    // }
    public function paymentStatusUpdate(Request $request, $id) {

        (new UserActivityController)->createActivity('PFI payment status updated.');

        $pfi = PFI::find($id);
        $pfi->payment_status = $request->payment_status;
        $pfi->save();
        return redirect()->back()->with('success', 'Payment Status Updated Successfully.');
    }
    public function relaesedAmountUpdate(Request $request, $id) {
        (new UserActivityController)->createActivity('PFI released amount updated.');

        $pfi = PFI::find($id);
        $pfi->released_amount = $request->released_amount;
        $pfi->released_date = $request->released_date;
        $pfi->save();
        return redirect()->back()->with('success', 'Payment released amount Successfully.');
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
                        $query->select('client_id','status','id','is_expired')->where('client_id', $request->client_id)
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
        // info($data['codes']);
       $data['master_model_id'] = $masterModel->id;
       return response($data);
    }
  

    public function getChildModels(Request $request) {
                
             $data = MasterModel::orderBy('id','DESC');
            //  info($request->all());

             if($request->is_child == 'Yes') {
                // info("child row add");
                $data = $data->with('loiItems.LOI')
                ->whereHas('loiItems.LOI', function($query)use($request){
                    $query->select('client_id','status','id','is_expired')
                    ->where('client_id', $request->customer)
                    ->whereIn('status', [LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL, LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED])
                    ->where('is_expired', false);
                }); 

                // info($data->pluck('id'));
             }

            if($request->model && $request->sfx) {
               
                $parentModel = MasterModel::where('model', $request->model)
                                    ->where('sfx', $request->sfx)->first();
                                    info($parentModel);

               $data = $data->where('steering', $parentModel->steering);
            //    info("after steering chcek");
            //    info($data->pluck('id'));
                $model_line = 'HIACE';
                if(str_contains($parentModel->modelLine->model_line, $model_line)){
                    // info("Hiace");
                    // info($parentModel->modelLine->model_line);
                    $data = $data->whereHas('modelLine', function($query)use($model_line){
                        $query->where('model_line', 'LIKE', '%'. $model_line .'%');
                    });
                }else{
                    // info("Not Hiace");
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
                // info($restrictedModelIds);
                if($restrictedModelIds) {
                    $data = $data->whereNotIn('id', $restrictedModelIds);
                }
            //  info($data->pluck('id'));
            }
           
            $data = $data->groupBy('model')->get();
            // info($data);
        return response($data);
       
    }
    public function getLOIItemDetails(Request $request) {
        // info($request->all());
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
