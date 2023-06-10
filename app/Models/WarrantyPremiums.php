<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyPremiums extends Model
{
    use HasFactory;
    protected $table = "master_model_descriptions";
    protected $fillable = [
        'warranty_policies_id',
        'vehicle_category1',
        'vehicle_category2',
        'eligibility_year',
        'eligibility_milage',
        'extended_warranty_period',
        'is_open_milage',
        'extended_warranty_milage',
        'claim_limit_in_aed',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',

    ];
}
