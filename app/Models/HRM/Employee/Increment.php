<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Increment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "increments";
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'other_allowances',
        'total_salary',
        'increament_effective_date',
        'increment_amount',
        'revised_basic_salary',
        'revised_other_allowance',
        'revised_total_salary',
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
