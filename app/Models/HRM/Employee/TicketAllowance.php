<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class TicketAllowance extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "ticket_allowances";
    protected $fillable = [
        'employee_id',
        'eligibility_year',
        'eligibility_date',
        'po_year',
        'po_number',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public function user() {
        return $this->hasOne(User::class,'id','employee_id');
    }
    public function createdBy() {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy() {
        return $this->hasOne(User::class,'id','updated_by');
    }
}
