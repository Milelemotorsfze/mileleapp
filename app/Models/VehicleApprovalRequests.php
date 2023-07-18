<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleApprovalRequests extends Model
{
    use HasFactory;
    protected $table = 'vehicle_detail_approval_requests';
    public $appends = [
        'old_exterior',
        'new_exterior',
        'old_interior',
        'new_interior'

    ];
    public function updatedBy() {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function approvedBy() {
        return $this->belongsTo(User::class,'approved_by','id');
    }
    public function getOldExteriorAttribute()
    {
        if($this->field == 'ex_colour') {
            $exterior = ColorCode::find($this->old_value);

            return $exterior->name ?? '';
        }
    }

    public function getNewExteriorAttribute()
    {
        if($this->field == 'ex_colour') {
            $exterior = ColorCode::find($this->new_value);

            return $exterior->name ?? '';
        }
    }
    public function getOldInteriorAttribute()
    {
        if($this->field == 'int_colour') {
            $interior = ColorCode::find($this->old_value);

            return $interior->name ?? '';
        }
    }
    public function getNewInteriorAttribute()
    {
        if($this->field == 'int_colour') {
            $interior = ColorCode::find($this->new_value);

            return $interior->name ?? '';
        }
    }
}
