<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleApprovalRequests extends Model
{
    use HasFactory;
    protected $table = 'vehicle_detail_approval_requests';
    public function updatedBy() {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function approvedBy() {
        return $this->belongsTo(User::class,'approved_by','id');
    }
}
