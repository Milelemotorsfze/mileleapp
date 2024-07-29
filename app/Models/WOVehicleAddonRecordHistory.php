<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOVehicleAddonRecordHistory extends Model
{
    use HasFactory;
    protected $table = "w_o_vehicle_addon_record_histories";
    protected $fillable = ['type','w_o_vehicle_addon_id','user_id','field_name', 'old_value', 'new_value','changed_at','cvm_id'];
    public $timestamps = false;

    protected $casts = [
        'changed_at' => 'datetime',
    ];
    protected $appends = [
        'field',
    ];
    public function getFieldAttribute() {
        $fieldMapping = [
            'addon_code' => 'Addon Code',
            'addon_description' => 'Addon Description',
            'addon_quantity' => 'Addon Quantity',
        ];
    
        return $fieldMapping[$this->field_name] ?? '';
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    // Define the inverse relationship to WOApprovalAddonDataHistory
    public function approvalAddonDataHistories()
    {
        return $this->hasMany(WOApprovalAddonDataHistory::class, 'wo_addon_history_id');
    }
    public function addon() {
        return $this->belongsTo(WOVehicleAddons::class,'w_o_vehicle_addon_id','id');
    }
}
