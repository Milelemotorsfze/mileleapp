<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BirthdayGift extends Model
{
    use HasFactory;
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
