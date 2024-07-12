<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WOVehicleRecordHistory extends Model
{
    use HasFactory;
    protected $table = "w_o_vehicle_record_histories";
    protected $fillable = ['type','w_o_vehicle_id','user_id','field_name', 'old_value', 'new_value','changed_at','comment_id'];
    public $timestamps = false;

    protected $casts = [
        'changed_at' => 'datetime',
    ];
    protected $appends = [
        'field',
    ];
    public function getFieldAttribute() {
        $fieldMapping = [
            'boe_number' => 'BOE Number',
            'brand' => 'Brand',
            'certification_per_vin' => 'Certification Per VIN',
            'deposit_received' => 'Deposit Received',
            'engine' => 'Engine',
            'exterior_colour' => 'Exterior Colour',
            'import_document_type' => 'Import Document Type',
            'interior_colour' => 'Interior Colour',
            'model_description' => 'Model Description',
            'model_year' => 'Model Year',
            'model_year_to_mention_on_documents' => 'Model Year To Mention On Documents',
            'modification_or_jobs_to_perform_per_vin' => 'Modification Or Jobs To Perform Per VIN',
            'ownership_name' => 'Ownership Name',
            'preferred_destination' => 'Preferred Destination',
            'special_request_or_remarks' => 'Special Request Or Remarks',
            'steering' => 'Steering',
            'territory' => 'Territory',
            'variant' => 'Variant',
            'vin' => 'VIN',
            'warehouse' => 'Warehouse',
        ];
    
        return $fieldMapping[$this->field_name] ?? '';
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function workOrderVehicle()
    {
        return $this->hasOne(WOVehicles::class,'id','w_o_vehicle_id');
    }
}
