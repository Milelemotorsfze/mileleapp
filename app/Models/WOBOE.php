<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOBOE extends Model
{
    use HasFactory;
    protected $table = "wo_boe";
    protected $fillable = ['wo_id','boe_number', 'boe', 'declaration_number','declaration_date','created_by','updated_by'];

    public function UpdatedBy()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
    public function CreatedBy()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class,'id','wo_id');
    }
    public function vehicles()
    {
        return $this->hasMany(WOVehicles::class, 'work_order_id', 'wo_id')
            ->where(function($query) {
                $query->where('boe_number', $this->boe_number)
                      ->orWhere(function($query) {
                          if ($this->boe_number == 1) {
                              $query->where('boe_number', 0);
                          }
                      });
            });
    }
    public function claim()
    {
        return $this->hasOne(WOBOEClaims::class,'wo_boe_id','id');
    }  
    public function penalty()
    {
        return $this->hasOne(BOEPenalty::class,'wo_boe_id','id');
    }  
}
