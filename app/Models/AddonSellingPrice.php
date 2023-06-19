<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonSellingPrice extends Model
{
    use HasFactory;
    protected $table = "addon_selling_prices";
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
    public function StatusUpdatedBy()
    {
        return $this->hasOne(User::class,'id','status_updated_by');
    }
}
