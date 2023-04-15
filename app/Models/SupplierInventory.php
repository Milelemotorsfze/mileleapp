<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class SupplierInventory extends Model
{
    use HasFactory;

    protected $appends = [
        'color_codes',
        'total_quantity',
        ];

    public const status = "supplier inventory";
    public const DEALER_TRANS_CARS = "Trans Cars";
    public const DEALER_MILELE_MOTORS = "Milele Motors";


    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class);
    }

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
    public function getColorCodesAttribute()
    {
        $modelId = $this->master_model_id;

        $colorCodes =  DB::table('supplier_inventories')
            ->select(DB::raw('count(color_code) AS color_code_count, color_code'))
            ->join('master_models',  'supplier_inventories.master_model_id', '=','master_models.id')
            ->where('master_models.id', '=', $modelId)
            ->where('veh_status', SupplierInventory::status)
            ->groupBy('color_code')
            ->get();
        return $colorCodes;

//        foreach ($colorCodes as $query) {
//
//            $colorCode = $query->color_code;
//            $colorCodeCount = $query->color_code_count;
//            $code_nameex = "(Colour Not Listed)  ".$colorCode;
//            $code = $colorCode;
//            $colorCodeLength = strlen($colorCode);
//            if($colorCodeLength == 5)
//            {
//                info($query->master_model_id);
//                $exterior = substr($code, 0, 3);
//            }
//            if ($colorCodeLength == 4)
//            {
//                info($query->master_model_id);
//                $altercolourcode = "0".$code;
//                $exterior = substr($altercolourcode, 0, 3);
//            }
//            return $exterior;
//            $parentColors = DB::table('color_codes')
//                ->select('parent')
//                ->where('code','=', $extcolour)
//                ->groupBy('parent')
//                ->get();
//        }

    }

}
