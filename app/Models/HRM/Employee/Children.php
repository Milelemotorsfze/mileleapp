<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;

class Children extends Model
{
    use HasFactory;
    protected $table = "childrens";
    protected $fillable = [
        'candidate_id',
        'employee_id',
        'child_name',
        'child_passport_number',
        'child_passport_expiry_date',
        'child_dob',
        'child_nationality',
    ];
    public function childNationality() {
        return $this->hasOne(Country::class,'id','child_nationality');
    }
}
