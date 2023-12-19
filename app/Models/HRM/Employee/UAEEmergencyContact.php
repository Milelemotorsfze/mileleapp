<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Masters\MasterPersonRelation;

class UAEEmergencyContact extends Model
{
    use HasFactory;
    protected $table = "u_a_e_emergency_contacts";
    protected $fillable = [
        'candidate_id',
        'employee_id',
        'name',
        'relation',
        'contact_number',
        'alternative_contact_number',
        'email_address',
    ];
    public function relationName() {
        return $this->hasOne(MasterPersonRelation::class,'id','relation');
    }
}
