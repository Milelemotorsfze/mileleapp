<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOVehicles extends Model
{
    use HasFactory;
    protected $table = "w_o_vehicles";
    protected $fillable = [
        'work_order_id',
        'vehicle_id',
        'boe_number',
        'vin',
        'brand',
        'variant',
        'engine',
        'model_description',
        'model_year',
        'model_year_to_mention_on_documents',
        'steering',
        'exterior_colour',
        'interior_colour',
        'warehouse',
        'territory',
        'preferred_destination',
        'import_document_type',
        'ownership_name',
        'modification_or_jobs_to_perform_per_vin',
        'certification_per_vin',
        'special_request_or_remarks',
        'shipment',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
