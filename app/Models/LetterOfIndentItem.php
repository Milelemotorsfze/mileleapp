<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterOfIndentItem extends Model
{
    use HasFactory, SoftDeletes;
//    public $timestamps = false;
    protected $appends = [
        'steering',
        'balance_quantity',
        'inventory_quantity'
    ];
    public function LOI()
    {
        return $this->belongsTo(LetterOfIndent::class,'letter_of_indent_id');
    }
//    public function Variant()
//    {
//        return $this->belongsTo(Varaint::class,'variant_name','name');
//    }
    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class);
    }
    public function getSteeringAttribute()
    {
       $mastermodel = MasterModel::where('model',$this->model)
                    ->where('sfx',$this->sfx)
                    ->first();
       if ($mastermodel)
       {
           return $mastermodel->steering;
       }
       return null;
    }
    public function getBalanceQuantityAttribute()
    {
       $totalQuantity = $this->quantity;
       $approvedQuantity = $this->approved_quantity;
       $balanceQuantity = $totalQuantity - $approvedQuantity;

       return $balanceQuantity;
    }
    public function getInventoryQuantityAttribute()
    {
        info('mastermodelid');
        info($this->master_model_id);
        $masterModel = MasterModel::find($this->master_model_id);
        $masterModelIds = MasterModel::where('steering', $masterModel->steering)
            ->where('model', $masterModel->model)
            ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();
        info("inventory count");
        info($masterModelIds);
        $inventoryCount = SupplierInventory::whereIn('master_model_id', $masterModelIds)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->whereNull('status')
            ->whereNull('eta_import')
            ->count();

        return $inventoryCount;
    }
}
