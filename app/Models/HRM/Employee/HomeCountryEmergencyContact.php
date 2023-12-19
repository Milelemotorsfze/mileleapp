<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\MasterPersonRelation;

class HomeCountryEmergencyContact extends Model
{
    use HasFactory;
    protected $table = "home_country_emergency_contacts";
    protected $fillable = [
        'candidate_id',
        'employee_id',
        'name',
        'relation',
        'contact_number',
        'alternative_contact_number',
        'email_address',
        'home_country_address',
    ];
    public function relationName() {
        return $this->hasOne(MasterPersonRelation::class,'id','relation');
    }
}
