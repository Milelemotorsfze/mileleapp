<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KitCommonItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kit_common_items';
    protected $fillable = [
        'addon_details_id',
        'item_id',
        'quantity',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function addon()
    {
        return $this->hasOne(AddonDetails::class,'id','addon_details_id');
    }
    public function item()
    {
        return $this->hasOne(AddonDetails::class,'id','item_id');
    }
}
