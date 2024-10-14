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
    // 753159

    
}
