<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterOfIndentItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $appends = [
        'steering',
        'balance_quantity',
        'inventory_quantity'
    ];
    public function LOI()
    {
        return $this->belongsTo(LetterOfIndent::class,'letter_of_indent_id');
    }
    public function Variant()
    {
        return $this->belongsTo(Varaint::class,'variant_name','name');
    }
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
        $mastermodel = MasterModel::where('model', $this->model)
            ->where('sfx', $this->sfx)
            ->first();
        $modelId = $mastermodel->id;

        $inventoryCount = SupplierInventory::with('masterModel')
            ->whereHas('masterModel', function ($query) use($modelId){
                $query->where('id', $modelId);
            })
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->count();

        return $inventoryCount;
    }
}
