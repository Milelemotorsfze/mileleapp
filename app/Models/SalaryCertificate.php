<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Masters\MasterJobPosition;

class SalaryCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'branch_name',
        'country_name',
        'purpose_of_request',
        'salary_certficate_request_detail',
        'passport_number',
        'issued_by',
        'company_branch',
        'salary_in_aed',
        'requested_job_title',
        'status',
        'requested_by',
        'requested_for',
        'comments',
        'joining_date',
        'creation_date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
    public function requestedFor()
    {
        return $this->belongsTo(User::class, 'requested_for');
    }
    public function jobTitle()
    {
        return $this->belongsTo(MasterJobPosition::class, 'requested_job_title');
    }
}
