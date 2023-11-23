<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Children extends Model
{
    use HasFactory;
    protected $table = "childrens";
    protected $fillable = [
        'employee_id',
        'child_name',
        'child_passport_number',
        'child_passport_expiry_date',
        'child_dob',
        'child_nationality',
    ];
}
