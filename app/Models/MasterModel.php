<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'is_deletable'
    ];
    public function variant()
    {
        return $this->belongsTo(Varaint::class,'variant_id','id');
    }
    public function supplierInventories()
    {
        return $this->hasMany(SupplierInventory::class);
    }
    public function loiItems()
    {
        return $this->hasMany(LetterOfIndentItem::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function modelLine()
    {
        return $this->belongsTo(MasterModelLines::class,'master_model_line_id','id');
    }
    public function TTCApprovalCountry()
    {
        return $this->hasMany(CountryTTCApprovalModel::class);
    }
    public function getIsDeletableAttribute() {

        $isExistinInventory = SupplierInventory::select('master_model_id')->where('master_model_id', $this->id)->count();
        if ($isExistinInventory <= 0) {
            $isExistinLOI = LetterOfIndentItem::select('master_model_id')->where('master_model_id', $this->id)->count();
            if($isExistinLOI <= 0) {
                $isExistinDemandList = DemandList::select('master_model_id')->where('master_model_id', $this->id)->count();
                if($isExistinDemandList <= 0) {
                    $isExistinPFIItem = PfiItem::select('master_model_id')->where('master_model_id', $this->id)->count();
                        if($isExistinPFIItem <= 0) {
                            $isExistinPO = PfiItemPurchaseOrder::select('master_model_id')->where('master_model_id', $this->id)->count();
                            if($isExistinPO <= 0) {
                                $isExistinVehicles = Vehicles::select('model_id')->where('model_id', $this->id)->count();
                                if($isExistinVehicles <= 0) {
                                    return true;
                                }
                            }
                        }
                }
            }
        }
        return false;
    }
}
