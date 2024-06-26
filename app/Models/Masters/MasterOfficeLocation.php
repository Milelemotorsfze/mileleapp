<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterOfficeLocation extends Model
{
    use HasFactory;
    protected $table = "master_office_locations";
    protected $fillable = [
        'name',
        'registered_company_name',
        'type',
        'address',
        'contact_number',
        'whatsapp_number',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
        
    ];
}
