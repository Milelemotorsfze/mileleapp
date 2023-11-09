<?php

namespace App\Models\Masters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDeparmentLocation extends Model
{
    use HasFactory;
    protected $table = "master_office_locations";
    protected $fillable = [
        'name',
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
