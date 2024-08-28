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
    public function getIsDeletableAttribute() {

        $isExistinInventory = SupplierInventory::where('master_model_id', $this->id)->count();

        if ($isExistinInventory <= 0) {
            $isExistinLOI = LetterOfIndentItem::where('master_model_id', $this->id)->count();

            if($isExistinLOI <= 0) {
                $isExistinDemandList = DemandList::where('master_model_id', $this->id)->count();
                if($isExistinDemandList <= 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
