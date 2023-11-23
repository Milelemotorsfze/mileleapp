<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSpokenLanguage extends Model
{
    use HasFactory;
    protected $table = "employee_spoken_languages";
    protected $fillable = [
        'employee_id',
        'language_id'
    ];
}
