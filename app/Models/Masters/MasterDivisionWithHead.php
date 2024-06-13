<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Masters\MasterDivisionWithHead;

class MasterDivisionWithHead extends Model
{
    use HasFactory;
    protected $table = "master_division_with_heads";
    protected $fillable = [
        'name',
        'division_head_id',
        'approval_handover_to',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function divisionHead() {
        return $this->hasOne(User::class,'id','division_head_id');
    }
    public function approvalHandoverTo() {
        return $this->hasOne(User::class,'id','approval_handover_to');
    }
}
