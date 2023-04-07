<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonDetails extends Model
{
    use HasFactory;
    protected $table = "addon_details";
    protected $fillable = [
        'addon_id',
        'addon_code',
        'purchase_price',
        'selling_price',
        'currency',
        'lead_time',
        'additional_remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'image'
    ];
    public function AddonTypes()
    {
        return $this->hasMany(AddonTypes::class,'addon_details_id','id');
        // ,'id','addon_details_id'
    }
    public function AddonName()
    {
        return $this->hasOne(Addon::class,'id','addon_id');
        // ,'id','addon_details_id'
    }
}
