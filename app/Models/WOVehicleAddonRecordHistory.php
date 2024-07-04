<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOVehicleAddonRecordHistory extends Model
{
    use HasFactory;
    protected $table = "w_o_vehicle_addon_record_histories";
    protected $fillable = ['type','w_o_vehicle_addon_id','user_id','field_name', 'old_value', 'new_value','changed_at'];
    public $timestamps = false;

    protected $casts = [
        'changed_at' => 'datetime',
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
