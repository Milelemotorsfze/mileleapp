<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVehicleAddonMapping extends Model
{
    use HasFactory;
    protected $table = "comment_vehicle_addon_mapping";
    protected $fillable = [
        'type','comment_vehicle_mapping_id','addon_id',
    ];
    public function addon()
    {
        return $this->hasOne(WOVehicleAddons::class,'id','addon_id')->withTrashed();
    }
    public function recordHistories()
    {
        return $this->hasMany(WOVehicleAddonRecordHistory::class, 'cvm_id', 'id');
    }
}
