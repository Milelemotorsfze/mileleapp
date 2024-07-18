<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentVehicleMapping extends Model
{
    use HasFactory;
    protected $table = "comment_vehicle_mapping";
    protected $fillable = [
        'type','comment_id','wo_id','vehicle_id',
    ];
    public function vehicle()
    {
        return $this->hasOne(WOVehicles::class,'id','vehicle_id')->withTrashed();
    }
    public function recordHistories()
    {
        return $this->hasMany(WOVehicleRecordHistory::class, 'comment_vehicle_id', 'id');
    }
    public function storeMappingAddons() {
        return $this->hasmany(CommentVehicleAddonMapping::class, 'comment_vehicle_mapping_id', 'id')->where('type', 'store');
    }
    public function updateMappingAddons() {
        return $this->hasmany(CommentVehicleAddonMapping::class, 'comment_vehicle_mapping_id', 'id')->where('type', 'update');
    }
    public function deleteMappingAddons() {
        return $this->hasmany(CommentVehicleAddonMapping::class, 'comment_vehicle_mapping_id', 'id')->where('type', 'delete');
    }
}
