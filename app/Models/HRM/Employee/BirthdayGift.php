<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
