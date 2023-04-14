<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blform extends Model
{
    use HasFactory;
    protected $table = 'bl_form';
    protected $fillable = [
        'bl_number',
        'so_number',
        'no_of_containers',
        'trackable_web',
        'looks_genuine',
        'shipper_details',
        'so_des_country',
        'veh_ext_country',
        'bl_des_country',
        'port',
        'bl_date',
        'realnoreal',
        'status',
        'bl_attachment',
        // 'created_by',
    ];
    public $timestamps = false;
}
