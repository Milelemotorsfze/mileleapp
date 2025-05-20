<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CompanyDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_name',
        'assigned_company',
        'domain_registrar',
        'email_server',
        'company_domain_code',
        'created_by',
        'updated_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
