<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = 'emp_profile';
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'nationality',
        'religion',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'contact_number',
        'company_number',
        'visa_type',
        'visa_status',
        'emirates_expiry',
        'address_uae',
        'address_home',
    ];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
