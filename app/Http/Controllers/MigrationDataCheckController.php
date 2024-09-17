<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\MasterModel;
use App\Models\Clients;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use App\Models\LoiTemplate;
use App\Models\LoiSoNumber;
use App\Models\ClientCountry;
use App\Models\LetterOfIndentDocument;
use Illuminate\Support\Facades\File;
use App\Models\clientDocument;

use Illuminate\Http\Request;

class MigrationDataCheckController extends Controller
{
    /**
     * To chcek pfi qty is <= LOI item qty.
     */

    public function PfiQtyCheck() {

        $qtyExceededLOI = [];
        $datas =  DB::table('migration_pfi_items')
        ->select('loi_number','loi_item_id')
        ->where('payment_status','PAID')
        ->selectRaw("SUM(pfi_qty) as total_pfi_qty")
        // ->whereNotIn('loi_item_id', $qtyExceededLOI)
        ->groupBy('loi_item_id')
        ->get();
      
        foreach($datas as $data){
            $isExistLOIItem = DB::table('migration_loi_items')
            ->where('id',$data->loi_item_id)->first();
            // dd($isExistLOIItem);
            if($data->total_pfi_qty > $isExistLOIItem->quantity){
                $qtyExceededLOI[] = $data->loi_id;
            }
        }

         return $qtyExceededLOI;
    }
    // Model line of loi item has matching in pfi items
    public function ModelineCheck() {
        $datas =  DB::table('migration_pfi_items')
        ->select('loi_number','loi_item_id','model_line_id')
        ->get();

        $inCorrectModelLine = [];

        foreach($datas as $data){
            $isExistLOIItem = DB::table('migration_loi_items')
                        ->where('id', $data->loi_item_id)
                        ->where('model_line_id', $data->model_line_id)->first();
            // dd($isExistLOIItem);
            if(!$isExistLOIItem){
                $inCorrectModelLine[] = $data->loi_item_id;
            }
        }

        return $inCorrectModelLine;
    }

    // model and sfx in loi Item is belongs To respective model line model and sfx in PFI Items

    public function modelSfxMatchCheckPFI(){
        $datas =  DB::table('migration_pfi_items')
        ->select('loi_number','loi_item_id','model_line_id','master_model_id')
        ->get();

        $masterModelNotExistItems = []; 

        foreach($datas as $data){
            $isExistLOIItem = DB::table('migration_loi_items')
                        ->where('id', $data->loi_item_id)
                        ->where('master_model_id', $data->master_model_id)->first();
            // dd($isExistLOIItem);
            if(!$isExistLOIItem){
                $masterModel = DB::table('migration_master_models')->where('id', $data->master_model_id)->first();
                $possibleMasterModelIds = DB::table('migration_master_models')
                            ->where('model_line_id', $masterModel->model_line_id)
                            ->pluck('id')->toArray();

                $isExist = DB::table('migration_loi_items')
                            ->where('id', $data->loi_item_id)
                            ->whereIn('master_model_id', $possibleMasterModelIds)->first();
                if(!$isExist) {
                    
                    $masterModelNotExistItems[] = $data->loi_item_id;
                }
            
            }
        }

     return $masterModelNotExistItems;
    }
    public function modelSfxMatchCheckPO() {

        $datas =  DB::table('migration_po_items')
        ->select('po_number','pfi_item_id','model_line_id','master_model_id')
        ->get();

        $masterModelNotExistItems = []; 

        foreach($datas as $data){
            $isExistPFIItem = DB::table('migration_pfi_items')
                        ->where('id', $data->pfi_item_id)
                        ->where('master_model_id', $data->master_model_id)->first();
            // dd($isExistLOIItem);
            if(!$isExistPFIItem){
                $masterModel = DB::table('migration_master_models')->where('id', $data->master_model_id)->first();
                $possibleMasterModelIds = DB::table('migration_master_models')
                            ->where('model_line_id', $masterModel->model_line_id)
                            ->pluck('id')->toArray();

                $isExist = DB::table('migration_pfi_items')
                            ->where('pfi_item_id', $data->pfi_item_id)
                            ->whereIn('master_model_id', $possibleMasterModelIds)->first();
                if(!$isExist) {
                    
                    $masterModelNotExistItems[] = $data->pfi_item_id;
                }
            
            }
        }

     return $masterModelNotExistItems;
    }
    public function PurcahseOrderQtyCheckwithPFIItems() {
        $PoQtyExceededLOI = [];
        $datas =  DB::table('migration_po_items')
        ->select('pfi_item_id')
        // ->where('payment_status','PAID')
        ->selectRaw("SUM(quantity) as total_po_qty")
        ->groupBy('pfi_item_id')
        ->get();
      
        foreach($datas as $data){
            $isExistLOIItem = DB::table('migration_pfi_items')
            ->where('pfi_item_id',$data->pfi_item_id)->first();
            // dd($isExistLOIItem);
            if($data->total_po_qty > $isExistLOIItem->pfi_qty){
                $PoQtyExceededLOI[] = $data->pfi_item_id;
            }
        }

         return $PoQtyExceededLOI;
    }
    public function UtilizedQtyCheckPo(){
        // check the utilized quantity is there in po items
       $incorrectUtilizedQty = [];
       $datas =  DB::table('migration_po_items')
        ->select('loi_item_id')
        ->where('payment_status', 'PAID')
        ->selectRaw("SUM(quantity) as total_po_qty")
        // ->whereNotIn('loi_item_id', $qtyExceededLOI)
        ->groupBy('loi_item_id')
        ->get();
        foreach($datas as $data) {
            $isExistLOIItem = DB::table('migration_loi_items')
                        ->where('id',$data->loi_item_id)->first();
          if($isExistLOIItem->utilized_quantity > $data->total_po_qty)  {
            $incorrectUtilizedQty[] = $data->loi_item_id;
          } 
        }

         return $incorrectUtilizedQty;
    }
    public function checkvariantinPO()  {
         // Variant id is same as model and sfx in the master model
       $variantIdNotMatching = [];
       $datas =  DB::table('migration_po_items')->get();

       foreach($datas as $data) {
       $model = DB::table('migration_master_models')
                    ->find($data->master_model_id);

        $possibleVariantIds = DB::table('migration_master_models')
                        ->where('model', $model->model)
                        ->where('sfx', $model->sfx)
                        ->pluck('variant_id')->toArray();
    
            if(!in_array($data->variant_id, $possibleVariantIds)) {
                $variantIdNotMatching[] = $data->po_number;
            }              
       }
       return $variantIdNotMatching;
    }
    public function uniqueCheckLOIItemsinLOI(){
        // check the duplicate LOI Items in LOI
        $duplicates = [];
        $LOIs = DB::table('migration_letter_of_indents')->whereNotIn('loi_number', $duplicates)->get();
        foreach ($LOIs as $LOI) {
        $loiIds = DB::table('migration_loi_items')->where('loi_number', $LOI->loi_number)->pluck('master_model_id')->toArray();
        
            if (count($loiIds) !== count(array_unique($loiIds))) {
                $duplicates[] = $LOI->loi_number;
            }
        }
        return $duplicates;
 
    }
    public function uniqueModel() {
        $models =  DB::table('migration_master_models')->orderBy('id','DESC')->get();
        foreach ($models as $model) {
            $isAlreadyExist =  DB::table('migration_master_models')->where('model', $model->model)
                ->where('sfx', $model->sfx)
                ->where('steering', $model->steering)
                ->where('model_year', $model->model_year)
                // ->where('model_line_id', $model->model_line_id)
                ->count();
            if($isAlreadyExist > 1) {
                return  redirect()->back()->withErrors('This Model and Sfx and Model Year combination is already existing.');
            }
        }
        
        return 1;
    }
    // to check the inventory model-sfx quantity is matching with unpaid po quantity.
    public function checkmodelSfxQTYWithInventory() {
        $alreadyUsedModelIds = [];
        $incorrectModelSfx = [];
            $datas =  DB::table('migration_po_items')
                            ->where('payment_status', 'UNPAID')
                            ->get();
        foreach($datas as $data) {
            $alreadyUsedModelIds[] = $data->master_model_id;
          
            $masterModel = DB::table('migration_master_models')->find($data->master_model_id);
            
            $sameModelIds =  DB::table('migration_master_models')
                                ->where('model', $masterModel->model)
                                ->where('sfx', $masterModel->sfx)
                                // ->whereIn('model_line_id', $data->model_line_id)
                                ->pluck('id')->toArray();
                               
                                
            // get the count of same model lines 
            $modelCountPO = DB::table('migration_po_items')
                                ->where('payment_status', 'UNPAID')
                                // ->where('dealer', 'Milele Motors')
                                ->where('vendor', $data->vendor)
                                ->whereIn('master_model_id',$sameModelIds)->sum('quantity');   
                                
             $modelCountInventory = DB::table('migration_supplier_inventories')
                                       ->where('model', $masterModel->model)
                                        ->where('sfx', $masterModel->sfx)
                                        ->where('dealer', 'Milele Motors')
                                        ->where('vendor', $data->vendor)
                                        ->count();  
                                        info("total count in po");
                                                    

          if($modelCountInventory < $modelCountPO)  {
            $incorrectModelSfx[] = $masterModel->model."-".$masterModel->sfx;
          } 
        }

         return $incorrectModelSfx;
    }
    // ensure the quantity is >= utilized quantity
    public function UtilizedQtyCheckLOIItems() {
        $qtyExceededLOI = [];
        $LOIItems = DB::table('migration_loi_items')->get();
        foreach ($LOIItems as $LOIItem) {
         if($LOIItem->quantity < $LOIItem->utilized_quantity) {
            $qtyExceededLOI[] = $LOIItem->loi_number;
         }
        }
        return $qtyExceededLOI;
    }
    public function customerNameUniquecheck() {
        $clients = Clients::where('is_demand_planning_customer',true)
            ->whereNotIn('id',['498','734','534','1497','556','1211','559','1577','693','750','825','826','919','1162','945','969'])
            ->get();
        foreach ($clients as $client) {
            $clientCount =  Clients::where('name', $client->name)
            ->where('is_demand_planning_customer',true)
           
            ->count();
          $duplicateClients =  Clients::where('name', $client->name)->get();
            if($clientCount > 1) {
               return $duplicateClients; 
            } 
        }
        return "All cutomers are unique name";
    }
    public function masterModelUnique(){
        $masterModels = MasterModel::get();
        foreach($masterModels as $masterModel) {
         $duplicateCount = MasterModel::where('model', $masterModel->model)
         ->where('sfx', $masterModel->sfx)
         ->where('steering', $masterModel->steering)->where('model_year', $masterModel->model_year)
         ->count();
 
         if($duplicateCount > 1) {
             return $duplicateCount; 
          } 
        }
        
        return "Master models are unique";
    }
    public function updateUtilizationQtyForMigratedData() {
            // make attribute sum calculation according to which qty u need
            $lois = LetterOfIndent::all();
            foreach($lois as $loi) {
                $loi->utilized_quantity =  $loi->total_loi_quantity;
                $loi->save();
            }

            return 1;
    }
    // Customer Id,LOI Date,Dealer,LOI Category,Status,is Expired.
    public function checkEnumLOI() {
            
    //    $loi = LetterOfIndent::whereNull('is_expired')->first();
    //    if($loi) {
    //     return $loi;
    //    }
    //    return 1;
    //   $loi = LetterOfIndent::whereNotIn('category',['Management Request','End User Changed','Quantity Inflate','Original','Special'])->get();
    // $customer = Clients::where('is_demand_planning_customer', true)
    //         ->whereNotIn('customertype', ['Individual','Company','Government'])->get();
    // $loi = LetterOfIndent::whereNotIn('dealers',['Trans Cars','Milele Motors'])->get();
    if($loi) {
        return $loi;
      }
      return 1;
    }
    public function index(Request $request)
    {
        $isPassport = [];
        // $isTradeLicense = [];
        $letterOfIndentDocuments = LetterOfIndentDocument::where('id',17)->get();
                                // return $letterOfIndentDocuments;
        foreach($letterOfIndentDocuments as $letterOfIndentDocument) {
            info($letterOfIndentDocument);
            $customerDoc = clientDocument::where('document', $letterOfIndentDocument->loi_document_file)->first();
            if(!$customerDoc) {
                info($letterOfIndentDocument->LOI->client->passport);
                info($letterOfIndentDocument->LOI->client->tradelicense);
                if($letterOfIndentDocument->LOI->client->passport || $letterOfIndentDocument->LOI->client->tradelicense) {
                    info("passport / treade license  exist");
                    $isPassport[] = $letterOfIndentDocument->loi_document_file;
                   
                }else{
                    info("other doc exist");
                    $old_path = public_path('LOI-Documents/'.$letterOfIndentDocument->loi_document_file);
                    $new_path = public_path('customer-other-documents/'.$letterOfIndentDocument->loi_document_file);
       
                   // return $new_path;
                   File::copy($old_path, $new_path);
                   $customerDoc = new clientDocument();
                   $customerDoc->document = $letterOfIndentDocument->loi_document_file;
                   $customerDoc->client_id = $letterOfIndentDocument->LOI->client_id;
                   $customerDoc->save();
                    
                }
               
            }
        }
        info("passport");
        info($isPassport);
     
        return $isPassport;
    }
    // transfer file from LOI Documents to Customer Documents
    public function migrateCustomerDocs() {
        $letterOfIndentDocument = LetterOfIndentDocument::find(1);
        $old_path = 'LOI-Documents/'.$letterOfIndentDocument->loi_document_file;

        return $letterOfIndentDocument;
        $move = File::move($old_path, $new_path);
    }
    public function migratecustomerCountries() {
        $clients = Clients::where('is_demand_planning_customer', true)->get();
      info($clients->count());
      foreach($clients as $client) {
        $clientCountry = new ClientCountry();
        $clientCountry->client_id = $client->id;
        $clientCountry->country_id = $client->country_id;
        $clientCountry->save();
      }
    }
    // add master model line id of respective variants
    public function chcekuniqueSonumberinLOI(){
        $loiSoNummbers = LoiSoNumber::all();
        foreach($loiSoNummbers as $loiSoNummber) {
            $duplicateCount = LoiSoNumber::where('letter_of_indent_id', $loiSoNummber->letter_of_indent_id)
            ->where('so_number', $loiSoNummber->so_number)
            ->count();
    
            if($duplicateCount > 1) {
                return $duplicateCount; 
             } 
           }
           
           return "all LOI have unique so number are unique";
    
    }
    public function LOIItemCodeCheck(){
        $loiItemCodes = DB::table('loi_item_codes')->get();
        foreach($loiItemCodes as $loiItemCode) {
            DB::table('letter_of_indent_items')
                ->where('id', $loiItemCode->loi_item_id)
                ->update([
                    'code' => $loiItemCode->code,
                ]);
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
