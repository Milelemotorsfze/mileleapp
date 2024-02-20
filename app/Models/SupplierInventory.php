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
    
    public const VEH_STATUS_SUPPLIER_INVENTORY = "supplier inventory";
    public const VEH_STATUS_VENDOR_CONFIRMED = "Vendor Confirmed";
    public const VEH_STATUS_LOI_APPROVED = "LOI Approved";
    public const VEH_STATUS_DELETED = "Deleted";
    public const DEALER_TRANS_CARS = "Trans Cars";
    public const DEALER_MILELE_MOTORS = "Milele Motors";
    public const UPLOAD_STATUS_ACTIVE = "Active";
    public const STATUS_DELIVERY_CONFIRMED = "Delivery Confirmed";
    public const UPLOAD_STATUS_INACTIVE = "Inactive";
    public const DN_STATUS_WAITING = "WAITING";


    protected $appends = [
        'color_codes',
        'total_quantity',
        'actual_quantity',
        'quantity_without_chasis'
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
        'upload_status',
        'delivery_note'
    ];
    public function masterModel()
    {
        return $this->belongsTo(MasterModel::class,'master_model_id','id');
    }
    public function interiorColor()
    {
        return $this->belongsTo(ColorCode::class,'interior_color_code_id','id');
    }
    public function exteriorColor()
    {
        return $this->belongsTo(ColorCode::class,'exterior_color_code_id','id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function letterOfIndentItem()
    {
        return $this->belongsTo(LetterOfIndentItem::class,'letter_of_indent_item_id','id');
    }
    public function pfi()
    {
        return $this->belongsTo(PFI::class,'pfi_id','id');
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchasingOrder::class,'purchase_order_id','id');
    }
    public function getTotalQuantityAttribute()
    {
        $masterModel = MasterModel::find($this->master_model_id);
        $masterModelIds = MasterModel:: where('model', $masterModel->model)
            ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();

        $supplierInventories = SupplierInventory::whereIn('master_model_id', $masterModelIds)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);

        if(request()->supplier_id){
            $supplierInventories = $supplierInventories->where('supplier_id', request()->supplier_id);
        }
        if(request()->dealers){
            $supplierInventories = $supplierInventories->where('whole_sales', request()->dealers);
        }
        if (!$supplierInventories) {
             return 0;
        }
         return $supplierInventories->count();
    }

    public function getActualQuantityAttribute()
    {
        $masterModel = MasterModel::find($this->master_model_id);
        $masterModelIds = MasterModel::where('model', $masterModel->model)
            ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();

        $supplierInventories = SupplierInventory::whereIn('master_model_id', $masterModelIds)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);

        if(request()->supplier_id){
            $supplierInventories = $supplierInventories->where('supplier_id', request()->supplier_id);
        }
        if(request()->dealers){
            $supplierInventories = $supplierInventories->where('whole_sales', request()->dealers);
        }

        if (!$supplierInventories) {
            return 0;
        }
        return $supplierInventories->count();
    }
    public function getQuantityWithoutChasisAttribute()
    {
        $masterModel = MasterModel::find($this->master_model_id);
        $masterModelIds = MasterModel::where('model', $masterModel->model)
            ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();

        $supplierInventories = SupplierInventory::whereIn('master_model_id', $masterModelIds)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->whereNull('chasis')
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE);

        if(request()->supplier_id){
            $supplierInventories = $supplierInventories->where('supplier_id', request()->supplier_id);
        }
        if(request()->dealers){
            $supplierInventories = $supplierInventories->where('whole_sales', request()->dealers);
        }

        if (!$supplierInventories) {
            return 0;
        }
        return $supplierInventories->count();
    }

    public function getColorCodesAttribute()
    {
        $masterModel = MasterModel::find($this->master_model_id);

        $supplierInventories =  DB::table('supplier_inventories')
            ->select(DB::raw('count(color_code) AS color_code_count, color_code'))
            ->join('master_models',  'supplier_inventories.master_model_id', '=','master_models.id')
            ->where('master_models.model', $masterModel->model)
            ->where('master_models.sfx', $masterModel->sfx)
            ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
            ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
//            ->whereNull('delivery_note')
            ->groupBy('color_code');
        if(request()->supplier_id){
            $supplierInventories = $supplierInventories->where('supplier_id', request()->supplier_id);
        }
        if(request()->dealers){
            $supplierInventories = $supplierInventories->where('whole_sales', request()->dealers);
        }

        return $supplierInventories->get();
    }

}
