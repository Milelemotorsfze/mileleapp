<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class BirthdayGift extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "birthday_gifts";
    protected $fillable = [
        'employee_id',
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
