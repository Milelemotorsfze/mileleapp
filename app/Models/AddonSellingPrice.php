<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonSellingPrice extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "addon_selling_prices";
    public const SELLING_PRICE_STATUS_PENDING = 'pending';
    protected $fillable = [
        'addon_details_id',
        'selling_price',
        'status',
        'status_updated_by',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function CreatedBy()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function UpdatedBy()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
    public function StatusUpdatedBy()
    {
        return $this->hasOne(User::class,'id','status_updated_by');
    }
    public function addonDetails()
    {
        return $this->hasMany(AddonDetails::class,'id','addon_details_id');
    }
    public function addonDetail()
    {
        return $this->hasOne(AddonDetails::class,'id','addon_details_id');
    }
}
