<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language;

class EmployeeSpokenLanguage extends Model
{
    use HasFactory;
    protected $table = "employee_spoken_languages";
    protected $fillable = [
        'candidate_id',
        'employee_id',
        'language_id'
    ];
    public function language() {
        return $this->hasOne(Language::class,'id','language_id');
    }
}
