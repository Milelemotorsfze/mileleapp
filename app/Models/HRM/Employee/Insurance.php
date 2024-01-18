<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insurance extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "insurances";
    protected $fillable = [
        'employee_id',
        'insurance_image',
        'insurance_policy_number',
        'insurance_card_number',
        'insurance_policy_start_date',
        'insurance_policy_end_date',
        'insurance_cancellation_done',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function user() {
        return $this->belongsTo(User::class,'employee_id');
    }
    public function createdBy() {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy() {
        return $this->hasOne(User::class,'id','updated_by');
    }
}
