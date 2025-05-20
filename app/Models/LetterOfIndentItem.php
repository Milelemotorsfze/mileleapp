<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LetterOfIndentItem extends Model
{
    use HasFactory, SoftDeletes;
   
    protected $appends = [
        'steering',
        'inventory_quantity',
        'loi_description',
    ];
    public function LOI()
    {
        return $this->belongsTo(LetterOfIndent::class,'letter_of_indent_id');
    }
    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class,'master_model_id','id');
    }
    public function pfiItems()
    {
        return $this->hasMany(PfiItem::class,'loi_item_id','id');
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
    public function getLoiDescriptionAttribute()
    {
        $Loi = LetterOfIndent::find($this->letter_of_indent_id);
        $LoiItem = LetterOfIndentItem::find($this->id);
        
        if($Loi->dealers == 'Trans Cars') {
            $loiDescription = $LoiItem->masterModel->transcar_loi_description ?? '';
        }else{
            $loiDescription = $LoiItem->masterModel->milele_loi_description ?? '';
        }

        return $loiDescription;
    }

    public function getInventoryQuantityAttribute()
    {
        $masterModel = MasterModel::find($this->master_model_id);
        $masterModelIds = MasterModel::where('model', $masterModel->model)
            ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();
        // confirm we should not conside the steering for unique combination
        $inventoryCount = SupplierInventory::whereIn('master_model_id', $masterModelIds)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
            ->whereNull('status')
            ->whereNull('delivery_note')
            ->count();

        return $inventoryCount;
    }
}
