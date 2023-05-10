<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class SupplierInventory extends Model
{
    use HasFactory;
    public $timestamps = false;
    public const VEH_STATUS_SUPPLIER_INVENTORY = "supplier inventory";
    public const VEH_STATUS_DELETED = "Deleted";
    public const DEALER_TRANS_CARS = "Trans Cars";
    public const DEALER_MILELE_MOTORS = "Milele Motors";
    public const UPLOAD_STATUS_ACTIVE = "Active";
    public const UPLOAD_STATUS_INACTIVE = "Inactive";

    protected $appends = [
        'color_codes',
        'total_quantity',
        'actual_quantity',
        'child_rows'
    ];
    protected $fillable = [
        'master_model_id',
        'engine_number',
        'chasis',
        'color_code',
        'color_name',
        'pord_month',
        'po_arm',
        'status',
        'eta_import',
        'upload_status'
    ];
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
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY);
        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $startDate = Carbon::parse(request()->start_date)->format('Y-m-d');
            $endDate =  Carbon::parse(request()->end_date)->format('Y-m-d');
            $supplierInventories = $supplierInventories->whereBetween('date_of_entry',[$startDate,$endDate]);
        }else{
            $supplierInventories = $supplierInventories->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);
        }

        if (!$supplierInventories) {
             return 0;
        }
         return $supplierInventories->count();
    }

    public function getActualQuantityAttribute()
    {
        $modelId = $this->master_model_id;
        $supplierInventories = SupplierInventory::with('masterModel')
            ->whereHas('masterModel', function ($query) use($modelId){
                $query->where('id', $modelId);
            })
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->whereNull('eta_import');

        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $startDate = Carbon::parse(request()->start_date)->format('Y-m-d');
            $endDate =  Carbon::parse(request()->end_date)->format('Y-m-d');
            $supplierInventories = $supplierInventories->whereBetween('date_of_entry',[$startDate,$endDate]);
        }else{
            $supplierInventories = $supplierInventories->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);
        }

        if (!$supplierInventories) {
            return 0;
        }
        return $supplierInventories->count();
    }

    public function getColorCodesAttribute()
    {
        $modelId = $this->master_model_id;
        $supplierInventories =  DB::table('supplier_inventories')
            ->select(DB::raw('count(color_code) AS color_code_count, color_code'))
            ->join('master_models',  'supplier_inventories.master_model_id', '=','master_models.id')
            ->where('master_models.id', '=', $modelId)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->whereNull('eta_import')
            ->groupBy('color_code');

        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $startDate = Carbon::parse(request()->start_date)->format('Y-m-d');
            $endDate =  Carbon::parse(request()->end_date)->format('Y-m-d');
            $supplierInventories = $supplierInventories->whereBetween('date_of_entry',[$startDate,$endDate]);
        }else{
            $supplierInventories = $supplierInventories->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);
        }

        return $supplierInventories->get();
    }
    public function getChildRowsAttribute() {
        info($this->master_model_id);
        info("clicked");
    }

}
