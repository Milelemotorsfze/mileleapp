<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierInventory extends Model
{
    use HasFactory;

    protected $appends = [
//        'variants',
        'total_quantity',
        ];
    public const status = "supplier inventory";

    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class);
    }

//    public function getVariantsAttribute()
//    {
//        $variant = Varaint::where('model', $this->model)
//            ->where('sfx', $this->sfx)
//            ->first();
//        if (!$variant) {
//             return "Variant Listed But Blanked";
//        }
//        return $variant->name;
//    }
    public function getTotalQuantityAttribute()
    {
        $modelId = $this->master_model_id;

        $supplierInventories = SupplierInventory::with('masterModel')
            ->whereHas('masterModel', function ($query) use($modelId){
                $query->where('id', $modelId);
            })
            ->where('veh_status', SupplierInventory::status)
            ->get();

        if (!$supplierInventories) {
             return 0;
        }
         return $supplierInventories->count();
    }

}
